<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	  //初始化方法
    	 function _initialize(){
                  $this->change_late();
                  $this->check_login(U(MODULE_NAME/CONTROLLER_NAME));
         }

         public function change_late(){
                  $time  = getdate();
                  $now_time = mktime(0,0,0,$time['mon'],$time['mday'],$time['year']);
                  M('borrow_repayment')->where('real_repayment_time <= repayment_time')->setField('is_late',0);
                  M('borrow_repayment')->where('repayment_time < '.$now_time.' AND is_repayment = 0 OR real_repayment_time > repayment_time')->setField('is_late',1);
         }

         protected function check_login($return){
                     if($_SESSION['name'] == ""){
                        $_SESSION['surl']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                        $this->error('未登录！请先登录！',U('Login/index'));
                     }
            }

}
