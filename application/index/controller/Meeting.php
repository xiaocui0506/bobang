<?php
/*商业会议 */
namespace app\index\controller;

use app\index\controller\Common;
use app\index\model\Meeting as M;


class Meeting extends Common
{
    //
  public function index(){

    $m = new M();
    $meet = $m->where(['status'=>1,'isdel'=>1])->order('id desc')->paginate(2,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('meet' , $meet);
    $this->assign('page' , $meet->render());
    return $this->fetch();
  }


  public function lists($id){
    $m = new M();
    $m->where(['id'=>$id])->setInc('preview');
    $meet = $m->where(['id'=>$id])->find();
    $expo['photo'] = explode(',',$meet['photo']);
    $this->assign('me' , $meet);
    return $this->fetch();
  }

  public function adds(){
    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    $this->assign('navall', $navall); // 推送行业

    $this->assign('meeting', config('meetType')); // 推送行业
    return $this->fetch();
  }

  public function add(){
    $m = new M();
    return $m->add();
  }
}
