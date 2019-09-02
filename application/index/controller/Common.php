<?php

namespace app\index\controller;



use think\Controller;

class Common extends Controller
{
  //
    public function __construct()
    {
      parent::__construct();
      $user_id = session('user_id');
//      if (!$user_id){
////        header('location: /index');exit();
////      }
    }


}
