<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {

    public function index(){
            $borrow = M('borrow');
            $all_borrow_money = $borrow->getfield('sum(borrow_money)');   //累计借款
            $all_borrow_procedures= $borrow->getfield('sum(borrow_procedures)');   //应收手续费
            $repayment_borrow_procedures = $borrow->where('is_procedures=1')->getfield('sum(borrow_procedures)');   //已收手续费
            $all_borrow_interest= $borrow->getfield('sum(borrow_interest)');   //总利息
            $now_borrow_interest= D('Index')->now_borrow_interest();     //到期应收利息
            $re_borrow_interest = D('Index')->all_borrow_interest();       //实收利息
            $all_late_money = M('borrow_repayment')->where('is_late_money =1 AND is_repayment_late_money = 0')->getfield('sum(late_penalty_money+late_interest_money)');        //应收逾期费
            $re_late_money= M('borrow_repayment')->where('is_late_money =1  AND is_repayment_late_money = 1')->getfield('sum(late_penalty_money+late_interest_money)');   //实收逾期费
            
            $this->assign("all_borrow_money",$all_borrow_money);
            $this->assign("all_borrow_procedures",$all_borrow_procedures);
            $this->assign("repayment_borrow_procedures",$repayment_borrow_procedures);
            $this->assign("re_borrow_interest",$re_borrow_interest);
            $this->assign("all_borrow_interest",$all_borrow_interest);
            $this->assign("now_borrow_interest",$now_borrow_interest);
            $this->assign("all_late_money",empty($all_late_money)?0:$all_late_money);
            $this->assign("re_late_money",empty($re_late_money)?0:$re_late_money);

           $this->assign("title","借贷管理系统");
           $this->display();
    }

    public function ajaxquery(){
              //这个月的开始和结束
              $this_month = D('Index')->m_frist_and_last();

              $order_column = $_GET['order'][0]['column'];
              $order_data = $_GET['columns'][$order_column]['data'];
              $dir = $_GET['order'][0]['dir'];
              //生成排序规则
              $order = $order_data.' '.$dir;
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

              //这个月的全部还款
              $borrow_repayment = M('borrow_repayment');
              $Model = new \Think\Model();
              $borrow_repayment_list= $Model
              ->table(array(
                         'borrow_repayment'=>'r',
                         'borrow'=>'b',
                         'user'=>'u'
                     )
               )
              ->where('r.borrow_uid = u.id AND r.borrow_id = b.id  AND repayment_time>='.$this_month['firstday'].' AND repayment_time<'.$this_month['lastday'].$where)
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
              ->where('r.borrow_uid = u.id AND r.borrow_id = b.id AND repayment_time>='.$this_month['firstday'].' AND repayment_time<'.$this_month['lastday'].$where)
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

                      $borrow_repayment_list[$key]['repayment_remarks'] = msubstr($value['repayment_remarks'],0,30);

                      $edit_repayment_url = U('Repayment/edit',array('id' => $value['id']));
                      $del_repayment_url = U('Repayment/confirm_repayment',array('id' => $value['id']));

                      $borrow_repayment_list[$key]['action'] = <<<HTML
                           <a title="编辑" href="$edit_repayment_url"><i class="fa fa-edit text-navy"></i></a>
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

    public function richeng(){

                // 这个月的开始和结束
                $m = ($_GET['m'] == 0)?"12":$_GET['m'];
                $y = ($_GET['m'] == 0)?$_GET['y']-1:$_GET['y'];
                $this_month = D('Index')->m_frist_and_last($m,$y);
                //这个月的全部还款
                $Model = new \Think\Model();
                $borrow_repayment_list= $Model
                ->table(array(
                           'borrow_repayment'=>'r',
                           'borrow'=>'b',
                           'user'=>'u'
                       )
                 )
                ->where('r.borrow_uid = u.id AND r.borrow_id = b.id  AND r.repayment_time>='.$this_month['firstday'].' AND repayment_time<'.$this_month['lastday'])
                ->field('r.*, u.name, b.borrow_money,b.borrow_duration')
                // ->field('u.name as title, r.repayment_time as start')
                ->select();

                //最后运行的sql，便于测试
                $sql = M()->getlastsql();

                $jsondata = array();
                // 处理数据
                foreach ($borrow_repayment_list as $key => $value) {
                          $jsondata[$key]['title'] = $value['name'];
                          if ($value['repayment_money']>$value['borrow_money']) {
                                    $jsondata[$key]['title'].= " 本金:".$value['borrow_money']."  利息:".($value['repayment_money']-$value['borrow_money']);
                          }else{
                                    $jsondata[$key]['title'].= " 利息:".$value['repayment_money'];
                          }
                          $jsondata[$key]['start'] = date('Y-m-d',$value['repayment_time']);
                          $jsondata[$key]['url'] = U('Repayment/edit',array('id' => $value['id']));
                          if ($value['is_repayment'] == 0) {
                                $jsondata[$key]['color'] = "#ed5565";
                          }
                          
                }

                $output = array(
                    "sql" => $sql,
                    "this_month" => $this_month,
                    "d" => $borrow_repayment_list,
                    "data" => $jsondata
                );
                $this->ajaxreturn($output,'json');
    }
}