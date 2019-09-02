<?php
/***
 * 会展列表+发布
 */
namespace app\index\controller;

use think\Controller;

use app\index\model\Expo as E;
class Expo extends Controller
{
    //
    public function index(){
      var_dump(111);
    }

    public function add(){
      $e = new E();
      return $e->add();
    }
}
