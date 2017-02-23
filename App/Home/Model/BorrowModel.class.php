<?php
namespace Home\Model;
use Think\Model;
class BorrowModel extends CommonModel{
	protected $autoCheckFields =false;

	 public function re_borrow_interest($borrow_id){

	 	$this_borrow = M('borrow')->where('id='.$borrow_id)->find();
	 	//当前用户已还款记录数
	 	$this_borrow_interest = M('borrow_repayment')->field('count(id) as count,sum(repayment_money) as all_repayment_interest')->where('borrow_id='.$borrow_id.' AND is_repayment = 1')->find();

	 	//判断已还款记录是否和还款期数相同
	 	$this_borrow['borrow_duration'] = intval($this_borrow['borrow_duration']);
	 	//if ($this_borrow['repayment_type'] == "到期本息" && $this_borrow_interest['count'] == 1) {
	 	//	return $this_borrow['borrow_interest'];
	 	//}else{
	 		if ($this_borrow_interest['count'] == $this_borrow['borrow_duration']) {        //相同 借款表内的利息值
	 			return $this_borrow['borrow_interest'];
	 		}else{	
	 			if ($this_borrow['repayment_type'] == "到期本息" ) {
	 				// $repayment_times = 1;
	 				return $this_borrow['borrow_interest'];
	 			}else{
	 				$repayment_times = $this_borrow['borrow_duration'];
	 			}
	 			return $this_borrow_interest['all_repayment_money']."(<span class=text-danger>".$this_borrow_interest['count'] ."</span>/".$repayment_times.")";    //不同 累加已还款记录内的值
	 		}
	 	//}

	 }
}
