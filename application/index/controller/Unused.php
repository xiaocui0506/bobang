<?php

namespace app\index\controller;

use think\Controller;

class Unused extends Controller
{
    //
  public function index(){
    var_dump(111);
  }

  public function add(){
    $u = new \app\index\model\Unused();
    return $u->add();
  }
}
