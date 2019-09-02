<?php
/***
 * 求购信息  + 发布
 */
namespace app\index\controller;

use think\Controller;

class Tobuy extends Controller
{
    //
  public function index(){
    return $this->fetch();
  }


  public function add(){
    $t = new \app\index\model\Tobuy();
    return $t->add();
  }
}
