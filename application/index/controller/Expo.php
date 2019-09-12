<?php
/***
 * 会展列表+发布
 */
namespace app\index\controller;

use app\index\controller\Common;

use app\index\model\Expo as E;
class Expo extends Common
{
    //
    public function index(){

      $e = new E();
      $expo = $e->where(['status'=>1,'isdel'=>1])->paginate(2,false,['type'=> '\base\share\Page','var_page' => 'p']);

      $this->assign('expo' , $expo);
      $this->assign('page' , $expo->render());

      return $this->fetch();
    }


    public function lists(){

      return $this->fetch();
    }

    public function add(){
      $e = new E();
      return $e->add();
    }
}
