<?php
return array(
	
	//***********************************SESSION设置**********************************
	    'SESSION_OPTIONS'         =>  array(
	        'name'                =>  'YZCSESSION',                    //设置session名
	        'expire'              =>  36000,                      //SESSION保存1小时
	        'use_trans_sid'       =>  1,                               //跨页传递
	        'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式
	    ),
);

