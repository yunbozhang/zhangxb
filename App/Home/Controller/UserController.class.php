<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController {

    public function index(){
            $this->assign("title","借款人列表-借贷管理系统");
            $this->display();
    }

    public function ajaxquery(){
              
              $order_column = $_GET['order'][0]['column'];
              $order_data = $_GET['columns'][$order_column]['data'];
              $dir = $_GET['order'][0]['dir'];
              //生成排序规则
              $order = $order_data.' '.$dir;
              //datatable全部数据   
              $columns = $_GET['columns'];

              // 查询规则
              $where = "";
              if (!empty($columns[1]['search']['value'])) {
                      $where.= "id =".$columns[1]['search']['value'];
              }
              if (!empty($columns[2]['search']['value'])) {
                      if (!empty($where)) {
                            $where.= " AND ";
                      };
                      $where.= "name LIKE '%".$columns[2]['search']['value']."%'";
              }
              
              //全部借款人
              $user_list = M('User')
               ->where($where)
               ->order($order)
               ->limit(I('get.start').','.I('get.length'))
               ->select();

               //最后运行的sql，便于测试
              $sql = M('User')->getlastsql();

              // 全部借款人数量
              $count = M('User')->count();// 查询总记录

              //处理数据
              foreach ($user_list as $key => $value) {
                      $user_list[$key]['add_time'] = date('Y-m-d',$value['add_time']);
                      
                      $user_list[$key]['all_borrow_money'] = M('borrow')->where('borrow_uid = '.$value['id'])->getfield('sum(borrow_money)');    //此借款人所有借款总额
                      $user_list[$key]['all_borrow_procedures'] = M('borrow')->where('borrow_uid = '.$value['id'].' AND is_procedures = 1')->getfield('sum(borrow_procedures)');   //此借款人已收手续费总额
                      $user_list[$key]['all_borrow_interest'] = M('borrow')->where('borrow_uid = '.$value['id'])->getfield('sum(borrow_interest)');   //此借款人总利息
                      $user_list[$key]['all_re_borrow_interest'] = D('user')->all_re_borrow_interest($value['id']);  //此借款人实收利息

                      $this_user_repayment_count = M('borrow_repayment')->where('borrow_uid = '.$value['id'])->count();    //此借款人所有还款数
                      $this_user_repayment_late_count = M('borrow_repayment')->where('borrow_uid = '.$value['id'].' AND is_late = 1')->count();    //此借款人所有逾期还款数

                      $user_list[$key]['late_rate'] = round($this_user_repayment_late_count/$this_user_repayment_count*100,2)."%";     //逾期率
                      $user_list[$key]['borrow_count'] = M('borrow')->where('borrow_uid = '.$value['id'].' AND renew_id = 0')->count();      //借款总数 不包含续借
                      $user_list[$key]['borrow_renew_count'] = M('borrow')->where('borrow_uid = '.$value['id'].' AND renew_id != 0')->count();      //借款续借总数

                      $user_edit_url = U('User/edit',array('id' =>$value['id']));
                      $del_user_url = U('User/del_user',array('id' =>$value['id']));
                      $user_list[$key]['action'] = <<<HTML
                     <a title="编辑" href="$user_edit_url"><i class="fa fa-edit text-navy"></i></a>
                     <a title="删除" onclick=if(confirm("确认删除这个借款人？")){location.href="$del_user_url ";}><i class="fa fa-trash text-danger"></i></a>
HTML;
              }

              $output = array(
                  "draw" => intval($_GET['draw']),
                  "recordsTotal" => $count,       //总数目
                  "recordsFiltered" => $count,        //过滤数目
                  "sql" => $sql,
                  "order" => $order,
                  "data" => $user_list
              );

              $this->ajaxreturn($output,'json');
    }

    //编辑借款人
    public function edit(){
           $user = M('user')->where('id='.I('get.id'))->find();
           $this->assign("user",$user);
           $this->assign("title","编辑借款人-借贷管理系统");
           $this->display();
    }

    //添加借款人
    public function add(){
    	     $this->assign("title","添加借款人-借贷管理系统");
      	$this->display();
    }
    //添加借款人方法
    public function add_act(){
       $data['name'] = I('get.name');
       $data['phone'] = I('get.phone');
       if (empty($data['name'])||empty($data['phone'])) {
              $this->error("某项没填！");
       }
       $data['add_time'] = time();
       if (M("user")->add($data)){
            $this->success("添加借款人成功！",U('User/Index'));
        }else{
            $this->error("添加借款人失败！请重试！");
       };
    }

    //删除借款人
    public function del_user(){
          $result = M('borrow_repayment')->where('borrow_uid='.I('get.id').' AND is_repayment=0')->select();
           if (!empty($result)) {
                $this->error("此借款人有未还借款！无法删除！",U('User/Index'));
            } 

          $result = M('user')->where('id='.I('get.id'))->delete();
          if ($result>0){
               $this->success("删除借款人成功！",U('User/Index'));
           }else{
               $this->error("删除借款人失败！");
          };

    }
}