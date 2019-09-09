<?php
/***
 * 求购信息  + 发布
 */
namespace app\index\controller;

use app\index\controller\Common;

use app\index\model\Tobuy as T;

class Tobuy extends Common
{
    //
  public function index(){

    $t = new T();

    $tobuy = $t->where(['status'=>1,'isdel'=>1])->select();

    $this->assign('tobuy' , $tobuy);

    return $this->fetch('lists');
  }

  public function lists($id){

    $t = new T();

    $t->where(['id'=>$id])->setInc('perview');

    $tobuy = $t->where(['id'=>$id])->find();
    $timediff = strtotime($tobuy['push_time']) - time();
    $days = intval($timediff/86400);

    $this->assign('days' , $days);
    $this->assign('tobuy' , $tobuy);
    return $this->fetch('index');
  }

  public function add(){
    $t = new \app\index\model\Tobuy();
    return $t->add();
  }
}
