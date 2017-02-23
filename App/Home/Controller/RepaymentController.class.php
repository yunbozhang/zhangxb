<?php
namespace Home\Controller;
use Think\Controller;
class RepaymentController extends CommonController {

    public function index(){
        $this->assign("title","还款列表-借贷管理系统");
        $this->display();
    }

    public function ajaxquery(){
        //$order_column = $_GET['order'][0]['column'];
        //$order_data = $_GET['columns'][$order_column]['data'];
        //$dir = $_GET['order'][0]['dir'];
        //生成排序规则
        //$order = $order_data.' '.$dir;
       $order = '';
        for ($i=0; $i <count($_GET['order']) ; $i++) { 
        		$order_column = $_GET['order'][$i]['column'];
        		$order_data = $_GET['columns'][$order_column]['data'];
        		$dir = $_GET['order'][$i]['dir'];

        		//生成排序规则
        		if (empty($order)) {
        			$order .= $order_data.' '.$dir;
        		}else{
        			$order .= ",".$order_data.' '.$dir;
        		}
        		
        }

        //datatable全部数据    
        $columns = $_GET['columns'];

        // 查询规则
        $where = "";
        if (!empty($columns[0]['search']['value'])) {
                $where.= " AND r.id =".$columns[0]['search']['value'];
        }
        if (!empty($columns[1]['search']['value'])) {
                $where.= " AND b.borrow_number LIKE '%".$columns[1]['search']['value']."%'";
        }
        if (!empty($columns[2]['search']['value'])) {
                $where.= " AND u.name LIKE '%".$columns[2]['search']['value']."%'";
        }

        // 全部还款
        $Model = new \Think\Model();
        $borrow_repayment_list= $Model
        ->table(array(
                   'borrow_repayment'=>'r',
                   'borrow'=>'b',
                   'user'=>'u'
               )
         )
        ->where('r.borrow_uid = u.id AND r.borrow_id = b.id'.$where)
        ->field('r.*, u.name, u.id as uid, b.borrow_number')
        ->order($order)
        ->limit(I('get.start').','.I('get.length'))
        ->select();

        //最后运行的sql，便于测试
        $sql = M()->getlastsql();

        //总数据数
        $count= $Model
        ->table(array(
                   'borrow_repayment'=>'r',
                   'borrow'=>'b',
                   'user'=>'u'
               )
         )
        ->where('r.borrow_uid = u.id AND r.borrow_id = b.id')
        ->field('r.*, u.name, u.id as uid, b.borrow_number')
        ->count();

        //处理数据
        foreach ($borrow_repayment_list as $key => $value) {

                $borrow_repayment_list[$key]['repayment_time'] = date('Y-m-d',$value['repayment_time']);
                if ($value['id_repayment'] == 0 && $value['real_repayment_time'] == 0) {
                            $borrow_repayment_list[$key]['real_repayment_time'] = "<span class='text-danger'>未还</span>";
                }else{
                            $borrow_repayment_list[$key]['real_repayment_time'] = date('Y-m-d',$value['real_repayment_time']);
                }

                $borrow_repayment_list[$key]['borrow_number'] = '<a href="'.U('Borrow/edit',array('id' => $value['borrow_id'])).'">'.$value['borrow_number'].'</a>';
                $borrow_repayment_list[$key]['name'] = '<a href="'.U('User/edit',array('id' => $value['borrow_uid'])).'">'.$value['name'].'</a>';
                
                if ($value['is_late'] == 0) {
                            $borrow_repayment_list[$key]['is_late'] = "未逾期";
                }else if($value['is_late'] == 1){
                            $borrow_repayment_list[$key]['is_late'] = "<span class='text-danger'>逾期</span>";
                }

                if ($value['is_repayment'] == 0) {
                            $borrow_repayment_list[$key]['is_repayment'] = "<span class='text-danger'>未还</span>";
                }else if($value['is_repayment'] == 1){
                            $borrow_repayment_list[$key]['is_repayment'] = "已还";
                }

                if (!empty($value['repayment_remarks'])) {
                            $borrow_repayment_list[$key]['id'].=   "<i class='fa fa-asterisk text-danger'></i>";
                }
                
                $borrow_repayment_list[$key]['repayment_remarks'] = $value['repayment_remarks'];    //备注
                $borrow_repayment_list[$key]['all_late_money'] = M('borrow_repayment')->where('id='.$value['id'])->getfield('sum(late_penalty_money+late_interest_money)');    //所有逾期费用
                
                $repayment_edit_url = U('Repayment/edit',array('id' => $value['id']));
                $del_repayment_url = U('Repayment/del_repayment',array('id' => $value['id']));

                $borrow_repayment_list[$key]['action'] = <<<HTML
                     <a title="编辑" href="$repayment_edit_url "><i class="fa fa-edit text-navy"></i></a>
                     <a title="确认还款" data-toggle="modal" data-target="#user_relation" onclick="change_id($value[id])"><i class="fa fa-check text-navy"></i></a>
                     <a title="删除" onclick=if(confirm("确认删除这条还款记录？")){location.href="$del_repayment_url";}><i class="fa fa-trash text-danger"></i></a>
HTML;
        };

        $output = array(
            "draw" => intval($_GET['draw']),
            "recordsTotal" => $count,       //总数目
            "recordsFiltered" => $count,        //过滤数目
            "sql" => $sql,
            "order" => $order,
            "data" => $borrow_repayment_list
        );
        $this->ajaxreturn($output,'json');

    }


    public function edit(){
        $borrow_repayment = M('borrow_repayment');
        $Model = new \Think\Model();
        $borrow_repayment_list = $Model
        ->table(array(
                   'borrow_repayment'=>'r',
                   'borrow'=>'b',
                   'user'=>'u'
               )
         )
        ->where('r.borrow_uid = u.id AND r.borrow_id = b.id AND r.id ='.I('get.id'))
        ->field('r.*, b.borrow_interest_rate ,u.name, u.id as uid')
        ->find();
        
        //判断逾期状态
        if ($borrow_repayment_list['repayment_time']< time()) {
                $is_late = 1;
        }

        $count_days = D('Index')->count_days($borrow_repayment_list['repayment_time']);
        //逾期罚息
        $borrow_procedures = number_format(($borrow_repayment_list['repayment_money']/1000)*$count_days,2);
        //逾期利息
        $borrow_interest_late = number_format(($borrow_repayment_list['repayment_money']*$borrow_repayment_list['borrow_interest_rate']/365/100)*$count_days,2);

        $this->assign("borrow_repayment_list",$borrow_repayment_list);
        $this->assign("is_late",$is_late);
        $this->assign("count_days",$count_days);
        $this->assign("borrow_procedures",$borrow_procedures);
        $this->assign("borrow_interest_late",$borrow_interest_late);

        $this->assign("title","编辑还款-借贷管理系统");
        $this->display();
    }
    public function edit_act(){
        $data['real_repayment_time'] = strtotime(I('post.real_repayment_time'));
        $data['is_repayment'] = I('post.is_repayment');
        $data['is_late'] = I('post.is_late');    //是否逾期
        $data['late_penalty_money'] = I('post.late_penalty_money');  //逾期罚息
        $data['late_interest_money'] = I('post.late_interest_money');  //逾期利息
        $data['is_late_money'] = I('post.is_late_money');     //是否收逾期
        $data['is_repayment_late_money'] = I('post.is_repayment_late_money');  //逾期是否已收   
        $data['repayment_remarks'] = I('post.repayment_remarks');
        if ($data['is_repayment_late_money']  == 1) {
            $data['late_repayment_time'] = time();
        }
        $borrow_repayment = M('borrow_repayment');
        $result = $borrow_repayment->where('id = '.I('post.id'))->save($data);
        if ($result>0){
             $this->success("更改还款信息成功！",U('Repayment/edit',array('id' => I('post.id'))));
         }else{
             $this->error("更改还款信息失败！".$borrow_repayment->getDbError());
        };
    }

    public function confirm_repayment(){
        $data['is_repayment'] = 1;
        $data['real_repayment_time'] = I('post.real_repayment_time')?strtotime(I('post.real_repayment_time')):time();
        $result = M('borrow_repayment')->where('id='.I('post.id'))->save($data);
        if ($result>0){
             $this->success("已更改该还款记录为已还！",U('Repayment/index'));
         }else{
             $this->error("更改还款记录失败！");
        };
    }
    public function del_repayment(){
        $borrow_repayment = M('borrow_repayment');
        $result = $borrow_repayment->where('id='.I('get.id'))->delete();
        if ($result>0){
             $this->success("删除还款记录成功！",U('Repayment/index'));
         }else{
             $this->error("删除还款记录失败！");
        };
    }

    public function chart(){
            // 每月应收与实收
            $arr_2016 = D('Repayment')->month_repayment();
            foreach ($arr_2016  as $key => $value) {
                    $str_2016 .= '{';
                    $str_2016 .='y:"'.$key.'月",';
                    $str_2016 .='a:'.$value['borrow_money'].',';
                    $str_2016 .='b:'.$value['re_borrow_money'];
                    $str_2016 .='},';
            }
            $arr_2015 = D('Repayment')->month_repayment('2015');

            foreach ($arr_2015  as $key => $value) {
                    $str_2015 .= '{';
                    $str_2015 .='y:"'.$key.'月",';
                    $str_2015 .='a:'.$value['borrow_money'].',';
                    $str_2015 .='b:'.$value['re_borrow_money'];
                    $str_2015 .='},';
            }
            $this->assign("str_2015",$str_2015);
            $this->assign("str_2016",$str_2016);
            $this->assign("title","还款统计-借贷管理系统");
            $this->display();
    }

}