<?php
/*
 * 招聘列表和添加
 * */
namespace app\index\controller;

use think\Controller;

use app\index\model\Recruit as R;

class Recruit extends Controller
{
    //
  public function index(){
    var_dump(111);
  }

  public function add(){
    $r = new R();
    return $r->add();
  }
}
