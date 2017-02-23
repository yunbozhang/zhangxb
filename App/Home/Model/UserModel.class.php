<?php
namespace Home\Model;
use Think\Model;
class UserModel extends CommonModel{
	protected $autoCheckFields =false;

	 public function all_re_borrow_interest($user_id){

	 	// 用户所有的借款
	 	$user_borrow = M('borrow')->where('borrow_uid='.$user_id)->select();

	 	$all_re_borrow_interest = 0;
	 	
	 	foreach ($user_borrow as $key => $value) {
	 		//当前用户已还款记录数
	 		$this_borrow_repayment = M('borrow_repayment')->field('count(id) as count,sum(repayment_money) as all_repayment_money')->where('borrow_id='.$value['id'].' AND is_repayment = 1')->find();
	 		
	 		if ($value['repayment_type'] == "到期本息" ) {
	 			$all_re_borrow_interest += $value['borrow_interest'] ;
	 		}else{
	 			if ($this_borrow_repayment['count'] == $value['borrow_duration']) {
	 				$all_re_borrow_interest += $value['borrow_interest'] ;
	 			}else{
	 				$all_re_borrow_interest += $this_borrow_repayment['all_repayment_money'];
	 			}
	 		}

	 	}

	 	return $all_re_borrow_interest;
	 }
}
