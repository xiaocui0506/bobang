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
      $expo = $e->where(['status'=>1,'isdel'=>1])->order('id desc')->paginate(2,false,['type'=> '\base\share\Page','var_page' => 'p']);

      $this->assign('expo' , $expo);
      $this->assign('page' , $expo->render());

      return $this->fetch();
    }


    public function lists($id){
      $e = new E();
      $e->where(['id'=>$id])->setInc('preview');
      $expo = $e->where(['id'=>$id])->find();
      $expo['photo'] = explode(',',$expo['photo']);
      $this->assign('ex' , $expo);
      return $this->fetch();
    }

    public function adds(){

      $navall = \cache('nav_type');
      if (!$navall){
        cache('nav_type',NavAll(),360);
        $navall = cache('nav_type');
      }
      $this->assign('navall', $navall); // 推送行业

      $this->assign('expoType', config('expoType')); // 推送行业

      return $this->fetch();
    }

    public function add(){
      $e = new E();
      return $e->add();
    }
}
