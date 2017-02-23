<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>跳转提示</title>
<link href="__PUBLIC__/css/bootstrap.min.css" rel="stylesheet">
<link href="__PUBLIC__/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="__PUBLIC__/css/animate.css" rel="stylesheet">
<link href="__PUBLIC__/css/style.css" rel="stylesheet">

</head>
<body class="gray-bg">
    	<div class="middle-box text-center animated fadeInDown">
		<?php if(isset($message)) {?>
		<h1><i class="fa fa-check text-success"></i></h1>
		<h2 class="text-success"><?php echo($message); ?></h2>
		<?php }else{?>
		<h1><i class="fa fa-close text-danger"></i></h1>
		<h2 class="text-danger"><?php echo($error); ?></h2>
		<?php }?>
		<p class="detail"></p>
		<p class="jump">
		页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait" class="text-success"><?php echo($waitSecond); ?></b>
		</p>
	</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
<script src="__PUBLIC__/js/jquery-2.1.1.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js"></script>
</body>
</html>
