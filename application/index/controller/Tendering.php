<?php
/*
 *
 *    添加招标发布
 * */
namespace app\index\controller;
use app\index\model\Tendering as T;

class Tendering extends Common
{
  /*  招标页面 */
  public function index(){

    return $this->fetch();
  }

  /* 招标 添加数据*/
  public function add(){
    $t = new T();
    return $t->add();

  }
}
