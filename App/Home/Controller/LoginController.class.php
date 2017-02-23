<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->assign("title","登录-借贷管理系统");
        $this->display();
    }

    public function login_act(){
       $name= I('post.name');
       $password= md5(I('post.password'));
       $result = M('admin')->where("name='%s' AND password='%s'",$name,$password)->find();
       if($result){
                    $_SESSION['name'] = $result['name'];
                    session(array('expire'=>20));
                    $this->success('登陆成功',empty($_SESSION['surl'])?U('Index/index'):$_SESSION['surl']);
       }else{
                    $this->error('登陆失败');
       }
    }

    public  function logout(){
        session(null);
        $this->success('欢迎再来!',U('Login/index'),3);
    }

}