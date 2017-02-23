<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model{
	protected $autoCheckFields =false;
	//总利息
	public function all_borrow_interest(){

		$all_borrow = M('borrow') ->field('id,borrow_duration,borrow_interest,repayment_type')->select();
		$re_borrow_interest = '';
		foreach ($all_borrow as $key => $value) {
			//当前用户已还款记录数
			$this_borrow = M('borrow_repayment')->field('count(id) as count,sum(repayment_money) as all_repayment_money')->where('borrow_id='.$value['id'].' AND is_repayment = 1')->find();

			//判断已还款记录是否和还款期数相同
			// $value['borrow_duration'] = intval($value['borrow_duration']);
			if ($value['repayment_type'] == "到期本息" ) {
				$re_borrow_interest += $value['borrow_interest'] ;
			}else{
				if ($this_borrow['count'] == $value['borrow_duration']) {        //相同 累加借款表内的利息值
					$re_borrow_interest += $value['borrow_interest'];
				}else{
					$re_borrow_interest += $this_borrow['all_repayment_money'];    //不同 则累加已还款记录内的值
				}
			}
		}
		return $re_borrow_interest;
	}

	//截止今日到期应收利息
	public function now_borrow_interest(){
		$all_borrow_repayment = M('borrow_repayment')->field('id,repayment_money,borrow_id')->where('is_repayment = 0 AND repayment_time<'.time())->select();
		$now_borrow_interest = 0;
		foreach ($all_borrow_repayment as $key => $value) {
			$this_borrow_money = M('borrow')->where('id='.$value['borrow_id'])->getfield('borrow_money');
			if ($value['repayment_money']>$this_borrow_money) {
				$now_borrow_interest += $value['repayment_money'] - $this_borrow_money;
			}else{
				$now_borrow_interest += $value['repayment_money'];
			}
		}
		return $now_borrow_interest;
	}

	//应收逾期，错误方法，未根据新的逾期罚息和逾期利息计算，未使用
	// public function all_late_money(){
	// 	$borrow_repayment =M('borrow_repayment');
	// 	$all_borrow_repayment = $borrow_repayment ->field('id,repayment_money,repayment_time')->select();
	// 	foreach ($all_borrow_repayment as $key => $value) {
	// 		if ($value['repayment_time'] < time()) {
	// 			$count_days = $this->count_days($value['repayment_time']);
	// 			$all_late_money += ($value['repayment_money']*$count_days)/1000;
	// 		}
	// 	}
	// 	return $all_late_money;
	// }

	//获取传入时间和当前时间此相差天数
	public function count_days($that_time){
		 $that_time = getdate($that_time);
		 $now_time = getdate(time());
		 $that_time_new=mktime(12,0,0,$that_time['mon'],$that_time['mday'],$that_time['year']);
		 $now_time_new=mktime(12,0,0,$now_time['mon'],$now_time['mday'],$now_time['year']);
		 return round(abs($that_time_new-$now_time_new)/86400);
	}

	//月的开始和结束时间戳
	public function m_frist_and_last($m="",$y){
	 	if($y=="") $y=date("Y");
	 	if($m=="") $m=date("m");
	 	$m=sprintf("%02d",intval($m));
	 	$y=str_pad(intval($y),4,"0",STR_PAD_RIGHT);
	 
	 	$m>12||$m<1?$m=1:$m=$m;
	 	$firstday=strtotime($y.$m."01000000");
	 	$firstdaystr=date("Y-m-01",$firstday);
	 	$lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));

	 	return array("month"=>$m,
	 		          "year"=>$y,
	 		     "firstday"=>$firstday,
	 		     "lastday"=>$lastday);
	}


}
