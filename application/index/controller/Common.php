<?php

namespace app\index\controller;



use app\index\model\Member;
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
      /*   判断用户是否有企业身份 */
      $bool = Member::where(['user_id'=>$user_id])->value('id');
      $this->assign('isiden',$bool);


      $this->assign('istime',getStrTime());

//      if (!$user_id){
////        header('location: /index');exit();
////      }
    }


}
