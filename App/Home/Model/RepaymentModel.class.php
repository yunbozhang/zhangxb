<?php
namespace Home\Model;
use Think\Model;
class RepaymentModel extends Model{
	protected $autoCheckFields =false;

 	public function today_start_end(){
 	    $t = time();
 	    $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
 	    $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
 	    return $today = array('start' =>$start , 'end'=>$end);
 	}

 	//获取一年总每月的应收和未收款
 	public function month_repayment($y){
 		$arr = array();
 	 	for ($i=1; $i < 13 ; $i++) { 
 	 		$m_frist_and_last = D('Index')->m_frist_and_last($i,$y);
 	 		//应收利息和本金
 	 		$borrow_money = M('borrow_repayment')
 	 		->where('repayment_time >='.$m_frist_and_last['firstday'].' AND '.'repayment_time <='.$m_frist_and_last['lastday'])
 	 		->getfield('sum(repayment_money)');
 	 		//应收手续费
 	 		$borrow_procedures = M('borrow')
 	 		->where('borrow_time >='.$m_frist_and_last['firstday'].' AND '.'borrow_time <='.$m_frist_and_last['lastday'])
 	 		->getfield('sum(borrow_procedures)');

 	 		//所有应收
 	 		$should_repayment = $borrow_money + $borrow_procedures;

 	 		//实收利息和本金
 	 		$re_borrow_money = M('borrow_repayment')
 	 		->where('is_repayment = 1 AND repayment_time >='.$m_frist_and_last['firstday'].' AND '.'repayment_time <='.$m_frist_and_last['lastday'])
 	 		->getfield('sum(repayment_money)');

 	 		//应收手续费
 	 		$re_borrow_procedures = M('borrow')
 	 		->where('is_procedures = 1 AND borrow_time >='.$m_frist_and_last['firstday'].' AND '.'borrow_time <='.$m_frist_and_last['lastday'])
 	 		->getfield('sum(borrow_procedures)');

 	 		$repayment = $re_borrow_money + $re_borrow_procedures;

 	 		$arr[$i] = array( 'borrow_money' =>empty($should_repayment)?0:$should_repayment,
 	 		   	           're_borrow_money' =>empty($repayment)?0:$repayment);
 	 	}	
 	 	return $arr;
 	}
}
