<?php

namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
    //用户登录页
  public function index(){

    $this->assign('title','博帮网');
    return $this->fetch();

  }

  /*用户登录*/
  public function login(){
    $post_data = input('post.');
    try{
      $login = new \app\admin\model\Login();
        $user = $login -> where(['account'=>$post_data['username']])->find();
        if ($user){
          if (md5($post_data['password']) != $user['password']){
            jsonResponse(-1,'','密码不正确');
          }else{
            session('adminuser',$user);
            session('user_id',$user['id']);
            jsonResponse(1,'','登录成功');
          }
        }else{
          jsonResponse(-1,'','用户名不正确');
        }
    }catch (Exception $e){
      jsonResponse(-1,'',$e->getMessage());
    }
  }

  /*用户他退出*/
  public function quit(){
    session(null);
    if (!session("?user")){
      header('location: /admin/login');exit();
    }
  }
}
