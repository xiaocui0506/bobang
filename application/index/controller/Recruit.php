<?php
/*
 * 招聘列表和添加
 * */
namespace app\index\controller;

use app\index\controller\Common;

use app\index\model\Recruit as R;

class Recruit extends Common
{
    //
  public function index(){

    $r = new R();
    $rec = $r->where(['status'=>1,'isdel'=>1])->select();

    $this->assign('rec' , $rec);

    return $this->fetch();
  }

  public function add(){
    $r = new R();
    return $r->add();
  }
}
