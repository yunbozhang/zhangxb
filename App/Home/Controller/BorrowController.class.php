<?php
namespace Home\Controller;
use Think\Controller;
class BorrowController extends CommonController {

    public function index(){
             $this->assign("title","借款列表-借贷管理系统");
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
                    if (!empty($columns[0]['search']['value'])) {
                            $where.= " AND b.id =".$columns[0]['search']['value'];
                    }
                    if (!empty($columns[1]['search']['value'])) {
                            $where.= " AND b.borrow_number LIKE '%".$columns[1]['search']['value']."%'";
                    }
                    if (!empty($columns[2]['search']['value'])) {
                            $where.= " AND u.name LIKE '%".$columns[2]['search']['value']."%'";
                    }

                    $Model = new \Think\Model();
                    $borrow_list  = $Model
                    ->table(array(
                               'borrow'=>'b',
                               'user'=>'u'
                           )
                     )
                    ->where('b.borrow_uid = u.id'.$where)
                    ->field('b.*, u.name')
                    ->order($order)
                    ->limit(I('get.start').','.I('get.length'))
                    ->select();

                    // 总数目
                    $count  = $Model
                    ->table(array(
                               'borrow'=>'b',
                               'user'=>'u'
                           )
                     )
                    ->where('b.borrow_uid = u.id')
                    ->field('b.*, u.name')
                    ->order('borrow_number')
                    ->count();
                //处理数据
                foreach ($borrow_list as $key => $value) {

                        $borrow_list[$key]['borrow_number'] = $value['borrow_number'];
                        $borrow_list[$key]['name'] = $value['name'];
                        $borrow_list[$key]['borrow_time'] = date('Y-m-d',$value['borrow_time']);
                        $borrow_end_time = M('borrow_repayment')->where('borrow_id='.$value['id'])->order('id desc')->getfield('repayment_time');
                        $borrow_list[$key]['end_time'] = date('Y-m-d',$borrow_end_time);
                        if ($borrow_end_time<time()) {
                                    $borrow_list[$key]['id'] =  "<del>$value[id]</del>";
                        }
                        if (!empty($value['borrow_remarks'])) {
                                    $borrow_list[$key]['id'].=   "<i class='fa fa-asterisk text-danger'></i>";
                        }
                        
                        if ($value['procedures_time'] !== 0) {
                                    $borrow_list[$key]['borrow_procedures'] =$value['borrow_procedures']."<span class='text-success'>(".date('Y-m-d',$value['procedures_time']).")</span>";
                        }

                        if ($value['is_procedures'] == 0) {
                                $borrow_list[$key]['is_procedures'] = "<span class='text-danger'>未还</span>";
                        }elseif ($value['is_procedures'] == 1) {
                                $borrow_list[$key]['is_procedures'] = "<span class='text-success'>已还</span>";
                                
                        }

                        
                        $borrow_list[$key]['re_borrow_interest'] = D('Borrow')->re_borrow_interest($value['id']);

                        $borrow_edit_url = U('Borrow/edit',array('id'=>$value['id']));
                        $borrow_renew_url = U('Borrow/renew',array('id'=>$value['id']));
                        $del_borrow_url = U('Borrow/del_borrow',array('id'=>$value['id']));
                        
                        $borrow_list[$key]['action'] =<<<HTML
                     <a title="编辑" href="$borrow_edit_url "><i class="fa fa-edit text-info"></i></button>
                     <a title="续借" href="$borrow_renew_url"><i class="fa fa-copy text-success"></i></a>
                     <a title="确认收取手续费" data-toggle="modal" data-target="#confirm_procedures" onclick="change_id($value[id])"><i class="fa fa-cny text-warning"></i></a>
                     <a title="删除" onclick="if(confirm('确认删除这条记录？')){location.href='$del_borrow_url';}"><i class="fa fa-trash text-danger"></i></a>
HTML;
                }

                $output = array(
                    "draw" => intval($_GET['draw']),
                    "recordsTotal" => $count,       //总数目
                    "recordsFiltered" => $count,        //过滤数目
                    "sql" => $sql,
                    "order" => $order,
                    "data" => $borrow_list
                );
                $this->ajaxreturn($output,'json');
    }

    // 编辑借款
    public function edit(){
            $Model = new \Think\Model();

            //获取借款信息
            $borrow_list  = $Model
            ->table(array(
                       'borrow'=>'b',
                       'user'=>'u'
                   )
             )
            ->where('b.borrow_uid = u.id AND b.id ='.I('get.id'))
            ->field('b.*, u.name, u.id as uid')
            ->find();
            $this->assign("borrow_list",$borrow_list);
            //获取用户列表
            $user_list = M("user")->order('id')->select();
            foreach ($user_list as $key => $value) {
                    if ($value['id'] == $borrow_list['borrow_uid']) {
                            unset($user_list[0]);
                    }
            }
            $this->assign("user_list",$user_list);
            //获取关联用户列表
            $borrow_user_relation_list  = $Model
            ->table(array(
                       'borrow_user_relation'=>'b',
                       'user'=>'u'
                   )
             )
            ->where('b.borrow_uid = u.id AND b.borrow_id ='.I('get.id'))
            ->field('b.*, u.name')
            ->select();
            $this->assign("borrow_user_relation_list",$borrow_user_relation_list);
            $this->assign("title","编辑借款-借贷管理系统");
            $this->display();
    }


    //手续费
    public function borrow_procedures(){
              $this->assign("title","手续费列表-借贷管理系统");
              $this->display();
    }

    public function borrow_procedures_ajaxquery(){
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
                            $where.= " AND b.id =".$columns[0]['search']['value'];
                    }
                    if (!empty($columns[1]['search']['value'])) {
                            $where.= " AND b.borrow_number LIKE '%".$columns[1]['search']['value']."%'";
                    }
                    if (!empty($columns[2]['search']['value'])) {
                            $where.= " AND u.name LIKE '%".$columns[2]['search']['value']."%'";
                    }

                    $Model = new \Think\Model();
                    $borrow_list  = $Model
                    ->table(array(
                               'borrow'=>'b',
                               'user'=>'u'
                           )
                     )
                    ->where('b.borrow_uid = u.id'.$where)
                    ->field('b.*, u.name')
                    ->order($order)
                    ->limit(I('get.start').','.I('get.length'))
                    ->select();

                    // 总数目
                    $count  = $Model
                    ->table(array(
                               'borrow'=>'b',
                               'user'=>'u'
                           )
                     )
                    ->where('b.borrow_uid = u.id')
                    ->field('b.*, u.name')
                    ->order('borrow_number')
                    ->count();
                //处理数据
                foreach ($borrow_list as $key => $value) {

                        $borrow_list[$key]['borrow_number'] = '<a href="'.U('Borrow/edit',array('id' => $value['borrow_id'])).'">'.$value['borrow_number'].'</a>';
                        $borrow_list[$key]['name'] = '<a href="'.U('User/edit',array('id' => $value['borrow_uid'])).'">'.$value['name'].'</a>';
                        $borrow_list[$key]['borrow_time'] = date('Y-m-d',$value['borrow_time']);
                        $borrow_end_time = M('borrow_repayment')->where('borrow_id='.$value['id'])->order('id desc')->getfield('repayment_time');
                        $borrow_list[$key]['end_time'] = date('Y-m-d',$borrow_end_time);
                        if ($borrow_end_time<time()) {
                                    $borrow_list[$key]['id'] =  "<del>$value[id]</del>";
                        }
                        if (!empty($value['borrow_remarks'])) {
                                    $borrow_list[$key]['id'].=   "<i class='fa fa-asterisk text-danger'></i>";
                        }
                        
                        if ($value['procedures_time'] != 0) {
                                    $borrow_list[$key]['borrow_procedures'] =$value['borrow_procedures']."<span class='text-success'>(已收".date('Y-m-d',$value['procedures_time']).")</span>";
                        }
                        
                        $borrow_edit_url = U('Borrow/edit',array('id'=>$value['id']));
                        $borrow_renew_url = U('Borrow/renew',array('id'=>$value['id']));
                        $del_borrow_url = U('Borrow/del_borrow',array('id'=>$value['id']));
                        
                        $borrow_list[$key]['action'] =<<<HTML
                     <a title="编辑" href="$borrow_edit_url "><i class="fa fa-edit text-info"></i></button>
                     <a title="续借" href="$borrow_renew_url"><i class="fa fa-copy text-success"></i></a>
                     <a title="确认收取手续费" data-toggle="modal" data-target="#confirm_procedures" onclick="change_id($value[id])"><i class="fa fa-cny text-warning"></i></a>
                     <a title="删除" onclick="if(confirm('确认删除这条记录？')){location.href='$del_borrow_url';}"><i class="fa fa-trash text-danger"></i></a>
HTML;
                }

                $output = array(
                    "draw" => intval($_GET['draw']),
                    "recordsTotal" => $count,       //总数目
                    "recordsFiltered" => $count,        //过滤数目
                    "sql" => $sql,
                    "order" => $order,
                    "data" => $borrow_list
                );
                $this->ajaxreturn($output,'json');
    }

    //编辑借款执行方法
    public function edit_act(){
        $data['is_procedures'] = I('post.is_procedures');
        $data['borrow_remarks'] = I('post.borrow_remarks');
        if (!empty($data['is_procedures'])) {
            $data['procedures_time'] = time();
        }
        $borrow = M('borrow');
        $result = $borrow->where('id='.I('post.id'))->save($data);
        if ($result>0){
             $this->success("更改借款信息成功！",U('Borrow/edit',array('id' => I('post.id'))));
         }else{
             $this->error("更改借款信息失败！");
        };
    }

    // 添加关联借款人方法
    public function add_user_relation_ect(){
           $data['borrow_uid'] = I('post.borrow_uid');
           $data['borrow_id'] = I('post.borrow_id');
           $have_relation= M('borrow_user_relation')->where($data)->find();
           if (!empty($have_relation)) {
                    $this->error("重复添加！你再检查一下，是不是已经关联过了？");
           }
           $borrow_user_relation = M('borrow_user_relation')->add($data);
            if ($borrow_user_relation == 0) {
                    $this->error("添加关联联系人失败！");
            }else{
                    $this->success("添加关联联系人成功！");
            }
    }

    public function del_borrow(){
            $result = M('borrow')->where('id='.I('get.id'))->delete();
            if ($result<0) {
                        $this->error("删除借款失败！");
            }
            $result = M('borrow_repayment')->where('borrow_id='.I('get.id'))->delete();
            if ($result<0) {
                        $this->error("删除相关的还款记录失败！请手动删除还款记录！");
            }else{
                        $this->success("删除借款成功！");
            }   
    }
    //添加借款
    public function add(){
    	$user_list = M("user")->order('id')->select();
    	$this->assign("user_list",$user_list);
           if (empty($user_list)) {
                      $this->error("请先添加借款人！",U('user/add'));
            }
    	$this->assign("title","添加借款-借贷管理系统");
           $this->display();
    }

    //添加借款方法
    public function add_act(){
    	$borrow = M("borrow");
    	$data['borrow_name'] = I('post.borrow_name');
    	$data['borrow_uid'] = I('post.borrow_uid');
    	$data['contract_number'] = I('post.contract_number');
    	$data['borrow_duration'] = I('post.borrow_duration');
    	$data['borrow_money'] = I('post.borrow_money');
            $data['borrow_interest_rate'] = I('post.borrow_interest_rate');
    	$data['borrow_procedures_rate'] = I('post.borrow_procedures_rate');
    	$data['borrow_time'] = strtotime(I('post.borrow_time'));
    	$data['repayment_type'] = I('post.repayment_type');
           $data['borrow_interest'] = I('post.borrow_money')*I('post.borrow_interest_rate')/(12*100)*I('post.borrow_duration');   //利息
           $data['borrow_procedures'] = I('post.borrow_money')*I('post.borrow_procedures_rate')/100;   //手续费
            if (empty($data['borrow_name'])  && empty($data['borrow_uid'])
                && empty($data['contract_number'])&& empty($data['borrow_duration'])&& empty($data['borrow_money'])
                && empty($data['borrow_interest_rate'])&& empty($data['borrow_procedures_rate'])&& empty($data['borrow_time'])
                && empty($data['repayment_type'])&& empty($data['borrow_interest'])&& empty($data['borrow_procedures'])) {
                    $this->error("某个选项没填");
            }
            $user_borrow_number = $borrow->where('borrow_uid='.$data['borrow_uid'].' AND renew_id = 0')->count();
            $data['borrow_number'] = $data['borrow_uid'].'.'.($user_borrow_number+1);
    	 $data['add_time'] = time();

    	$borrow_id = $borrow->add($data);

    	if($borrow_id == 0){
    	     $this->success("添加借款失败！请重试！");
    	};

    	//借款的月数
    	if (isset($data['borrow_duration']) && $data['borrow_duration']>0){
    		  $borrow_duration = $data['borrow_duration'];
    	}

    	//借款的总金额
    	if (isset($data['borrow_money']) && $data['borrow_money']>0){
    		  $borrow_money = $data['borrow_money'];
    	}else{
    		  return "";
    	}
    	
    	//借款的年利率
    	if (isset($data['borrow_interest_rate']) && $data['borrow_interest_rate']>=0){
    		  $borrow_interest_rate = $data['borrow_interest_rate'];
    	}else{
    		  return "";
    	}
    	
    	
    	//借款的时间
    	if (isset($data['borrow_time']) && $data['borrow_time']>0){
    		  $borrow_time = $data['borrow_time'];
    	}else{
    		  $borrow_time = time();
    	}
    	
    	//月利率
    	$month_interest_rate = $borrow_interest_rate/(12*100);
    	
    	//月利息
    	$month_interest = $borrow_money*$month_interest_rate;

    	if ($data['repayment_type'] == "付息还本") {  //付息还本
    		for($i=0;$i<$borrow_duration;$i++){
    		 	$borrow_repayment['borrow_id'] = $borrow_id;
    		 	$borrow_repayment['borrow_uid'] = $data['borrow_uid'];
    		 	$borrow_repayment['repayment_money'] = round($month_interest ,4);
    		 	$borrow_repayment['repayment_time'] = $this->get_times(array("time"=>$borrow_time,"num"=>$i+1));
    		 	$borrow_repayment['add_time'] = time();
    		 	if ($i+1 == $borrow_duration){
    		  		$borrow_repayment['repayment_money'] = $borrow_money + round($month_interest,4); //最后一个月还利息+本金
    		 	}
    		 	$Br = M('borrow_repayment');
    		 	$borrow_repayment_id = $Br->add($borrow_repayment);
    		 	if ($borrow_repayment_id == 0) {
    		 		$this->error("插入还款记录失败！");
    		 	}
                
               unset($borrow_repayment);
    		}
    	}else if ($data['repayment_type'] == "到期本息" || $borrow_duration <1) {   //到期本息
                        $borrow_repayment['borrow_id'] = $borrow_id;
                        $borrow_repayment['borrow_uid'] = $data['borrow_uid'];
    		  $borrow_repayment['repayment_money'] = $borrow_money + $month_interest*$borrow_duration;   //最后一次一次性还完
                        if ($borrow_duration<1) {
                                $borrow_repayment['repayment_time'] = strtotime("+15 day",$borrow_time);
                        }else{
    		          $borrow_repayment['repayment_time'] = $this->get_times(array("time"=>$borrow_time,"num"=>$borrow_duration));
                        }
    		  $borrow_repayment['add_time'] = time();
                        $Br = M('borrow_repayment');
                        $borrow_repayment_id = $Br->add($borrow_repayment);
                        if ($borrow_repayment_id == 0) {
                                $this->error("插入还款记录失败！");
                        }
    	}else{
    		$this->error("没有这个还款方式啊！！");
    	}

        if($borrow_repayment_id > 0){
             $this->success("添加还款记录成功！",U('Borrow/Index'));
         }else{
             $this->error("添加还款记录失败！请重试！");
        };
    }

    //支付手续费
    public function confirm_procedures(){
                $data['is_procedures'] = 1;
                $data['procedures_time'] = I('post.procedures_time')?strtotime(I('post.procedures_time')):time();
                $result = M('borrow')->where('id='.I('post.id'))->save($data);
                if ($result>0){
                     $this->success("更改借款信息成功！",U('Borrow/index'));
                 }else{
                     $this->error("更改借款信息失败！");    
                };
    }


    // 续借
    public function renew(){
          $Model = new \Think\Model();

          //获取借款信息
          $borrow_list  = $Model
          ->table(array(
                     'borrow'=>'b',
                     'user'=>'u'
                 )
           )
          ->where('b.borrow_uid = u.id AND b.id ='.I('get.id'))
          ->field('b.*, u.name, u.id as uid')
          ->find();
          $this->assign("borrow_list",$borrow_list);
          $this->assign("title","添加续借-借贷管理系统");
           $this->display();
    }

    // 续借方法
    public function renew_act(){
                $data['borrow_uid'] = I('post.borrow_uid');
                $data['borrow_number'] = I('post.borrow_number');
                $data['contract_number'] = I('post.contract_number');
                $data['borrow_duration'] = I('post.borrow_duration');
                $data['borrow_money'] = I('post.borrow_money');
                $data['borrow_interest_rate'] = I('post.borrow_interest_rate');
                $data['borrow_procedures_rate'] = I('post.borrow_procedures_rate');
                $data['borrow_time'] = strtotime(I('post.borrow_time'));
                $data['repayment_type'] = I('post.repayment_type');
                $data['renew_id'] = I('post.renew_id');
                $data['borrow_interest'] = I('post.borrow_money')*I('post.borrow_interest_rate')/(12*100)*I('post.borrow_duration');   //利息
                $data['borrow_procedures'] = I('post.borrow_money')*I('post.borrow_procedures_rate')/100;   //手续费
                $borrow_number = explode(".",$data['borrow_number']);
                $data['renew_id'] = M('borrow')->where('borrow_number='.$borrow_number[0].'.'.$borrow_number[1])->getfield('id');   //获取续借的id
                $renew_borrow_count= M("borrow")->where('renew_id='.$data['renew_id'])->count();    //
                $data['borrow_number'] = $borrow_number[0].'.'.$borrow_number[1].'.'.($renew_borrow_count+1);   //拼接借款序号
                $data['add_time'] = time();
                $borrow_id = M("borrow")->add($data);
                if($borrow_id == 0){
                     $this->error("添加借款失败！请重试！");
                };
                $borrow_money = M("borrow")->where('id='.I('post.renew_id'))->getfield('borrow_money');
                $repayment_money = M("borrow_repayment")->where('borrow_id='.I('post.renew_id'))->order('id desc')->getfield('repayment_money',1);
                $result= M("borrow_repayment")->where('borrow_id='.I('post.renew_id'))->order('id desc')->setField('repayment_money',($repayment_money-$borrow_money),1);
                if ($result == 0) {
                        $this->error("更新借款信息失败！请重试！");
                };

                //借款的月数
                if (isset($data['borrow_duration']) && $data['borrow_duration']>0){
                      $borrow_duration = $data['borrow_duration'];
                }

                //借款的总金额
                if (isset($data['borrow_money']) && $data['borrow_money']>0){
                      $borrow_money = $data['borrow_money'];
                }else{
                      return "";
                }
                
                //借款的年利率
                if (isset($data['borrow_interest_rate']) && $data['borrow_interest_rate']>=0){
                      $borrow_interest_rate = $data['borrow_interest_rate'];
                }else{
                      return "";
                }
                
                
                //借款的时间
                if (isset($data['borrow_time']) && $data['borrow_time']>0){
                      $borrow_time = $data['borrow_time'];
                }else{
                      $borrow_time = time();
                }
                
                //月利率
                $month_interest_rate = $borrow_interest_rate/(12*100);
                
                //月利息
                $month_interest = $borrow_money*$month_interest_rate;

                if ($data['repayment_type'] == "付息还本") {  //付息还本
                    for($i=0;$i<$borrow_duration;$i++){
                        $borrow_repayment['borrow_id'] = $borrow_id;
                        $borrow_repayment['borrow_uid'] = $data['borrow_uid'];
                        $borrow_repayment['repayment_money'] = round($month_interest ,4);
                        $borrow_repayment['repayment_time'] = $this->get_times(array("time"=>$borrow_time,"num"=>$i+1));
                        $borrow_repayment['add_time'] = time();
                        if ($i+1 == $borrow_duration){
                            $borrow_repayment['repayment_money'] = $borrow_money + round($month_interest,4); //最后一个月还利息+本金
                        }
                        $Br = M('borrow_repayment');
                        $borrow_repayment_id = $Br->add($borrow_repayment);
                        if ($borrow_repayment_id == 0) {
                            $this->error("插入还款记录失败！");
                        }
                           
                          unset($borrow_repayment);
                    }
                }else if ($data['repayment_type'] = "到期本息" || $borrow_duration<1) {   //到期本息
                                   $borrow_repayment['borrow_id'] = $borrow_id;
                                   $borrow_repayment['borrow_uid'] = $data['borrow_uid'];
                    $borrow_repayment['repayment_money'] = $borrow_money + $month_interest*$borrow_duration;   //最后一次一次性还完
                      if ($borrow_duration<1) {
                             $borrow_repayment['repayment_time'] = strtotime("+15 day",$borrow_time);
                      }else{
                             $borrow_repayment['repayment_time'] = $this->get_times(array("time"=>$borrow_time,"num"=>$borrow_duration));
                      }
                    $borrow_repayment['add_time'] = time();
                       $Br = M('borrow_repayment');
                       $borrow_repayment_id = $Br->add($borrow_repayment);
                       if ($borrow_repayment_id == 0) {
                           $this->error("插入还款记录失败！");
                       }
                }else{
                    $this->error("没有这个还款方式啊！！");
                }

                   if($borrow_repayment_id > 0){
                        $this->success("添加还款记录成功！",U('Borrow/Index'));
                    }else{
                        $this->error("添加还款记录失败！请重试！");
                   };

    }

    //获取指定时间后面的指定数量月的时间
    public function get_times($data=array()){
    	
    	//获得相差月的时间
    	if (isset($data['time']) && $data['time']!=""){
    		$time = $data['time'];//时间
    	}elseif (isset($data['date']) && $data['date']!=""){
    		$time = strtotime($data['date']);//日期
    	}else{
    		$time = time();//现在时间
    	}

    	if (isset($data['type']) && $data['type']!=""){ 
    		$type = $data['type'];//时间转换类型，有day week month year
    	}else{
    		$type = "month";
    	}

    	if (isset($data['num']) && $data['num']!=""){ 
    		$num = $data['num'];
    	}else{
    		$num = 1;
    	}
    	
    	if ($type=="month"){
    		$month = date("m",$time);
    		$year = date("Y",$time);
    		$_result = strtotime("$num month",$time);
    		$_month = (int)date("m",$_result);

    		if ($month+$num>12){
    			$_num = $month+$num-12;
    			$year = $year+1;
    		}else{
    			$_num = $month+$num;
    		}
    	
    		if ($_num!=$_month){
    			$_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01 "));
    		}
    	}else{
    		$_result = strtotime("$num $type",$time);
    	}

    	if (isset($data['format']) && $data['format']!=""){ 
    		return date($data['format'],$_result);
    	}else{
    		return $_result;
    	}
    }
}