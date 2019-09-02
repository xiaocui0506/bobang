<?php
/*  二级商家信息  20190814*/

namespace app\index\controller;
use think\Request;
use app\index\model\Busrelease as B ;
class Busrelease extends Common
{
  /*  二级商家页面 */
  public function index(){

      return $this->fetch();
  }

  /* 二级商家 添加数据*/
  public function add(){
    $b = new B;
    return $b->add();

  }

}