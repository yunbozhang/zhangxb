<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>借贷管理系统 安装</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="/Public/install/css/bootstrap.css-v=2.3.2.css" rel="stylesheet">
        <link href="/Public/install/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="/Public/install/css/install.css" rel="stylesheet">

        <script src="/Public/install/js/jquery.min.js-v=2.1.4"></script>
        <script src="/Public/install/js/bootstrap.js"></script>

        <link rel="shortcut icon" href="/Public/img/favicon.ico" />
    </head>
    <style>
        #step li a{
            color: #fff;
        }
        #step li{
            margin-top: 5px;
        }
        .active{
            margin-top: 5px;
        }
        .active a{
            background: #ED5565 !important;
            border-radius: 10%;
        }
    </style>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!-- Navbar
        ================================================== -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner" style="background:#1AB394;">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" target="_blank" href="http://www.yangzhongchao.com/">草原</a>
                    <div class="nav-collapse collapse">
                    	<ul id="step" class="nav">
                    		
    <li class="active"><a href="javascript:;">安装协议</a></li>
    <li><a href="javascript:;">环境检测</a></li>
    <li><a href="javascript:;">创建数据库</a></li>
    <li><a href="javascript:;">安装</a></li>
    <li><a href="javascript:;">完成</a></li>

                    	</ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                
    <h1>借贷管理系统 安装协议</h1>
    <p>序言：借贷管理系统基于ThinkPHP3.2.3开发，前端使用H+框架，添加借款后，自动计算每月的回款日期，并可通过统计表和柱状图直观的显示还款情况</p>
    <p>
    感谢：本程序使用了ThinkPHP,BootStrap,以及网络上一些插件，在这里感谢ThinkPHP,BootStrap的开源，为本程序带来了很强大的程序基础。
    </p>
    <p>1.作者博客地址网址：<a href="http://www.yangzhongchao.com/">草原</a> 作者：羊种草</p>

    <p>2.如果您对本程序有BUG提交或者功能建议，可以发送邮件到作者邮箱706597125@qq.com或者在博客留言</p>

    <p>3.希望您在使用过程中可以加入讨论群分享使用经验，qq群号：422475804</p>


            </div>
        </div>


        <!-- Footer
        ================================================== -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	
    <a class="btn btn-primary btn-large" href="<?php echo U('Install/step1');?>">同意安装协议</a>
    <a class="btn btn-large" onclick="CloseWebPage()">不同意</a>

                </div>
            </div>
        </footer>
    </body>
    <script>
        function CloseWebPage(){
         if (navigator.userAgent.indexOf("MSIE") > 0) {
          if (navigator.userAgent.indexOf("MSIE 6.0") > 0) {
           window.opener = null;
           window.close();
          } else {
           window.open('', '_top');
           window.top.close();
          }
         }
         else if (navigator.userAgent.indexOf("Firefox") > 0) {
          window.location.href = 'about:blank ';
         } else {
          window.opener = null;
          window.open('', '_self', '');
          window.close();
         }
        }
    </script>  
</html>