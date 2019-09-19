<?php

namespace app\index\controller;



use app\index\model\User;
use think\Controller;

class Common extends Controller
{
  //
    public function __construct()
    {
      parent::__construct();
      $user_id = session('user_id');

      /*判断用户是否登录*/
      $user = User::where(['id'=>$user_id])->field('user_name')->find();
      $this->assign('user',$user);
      $this->assign('istime',getStrTime());

//      if (!$user_id){
////        header('location: /index');exit();
////      }
    }


}
