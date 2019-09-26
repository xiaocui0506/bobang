<?php
/*商业会议 */
namespace app\index\controller;

use app\index\controller\Common;
use app\index\model\Meeting as M;


class Meeting extends Common
{
    //
  public function index(){
      $map['status'] = 1;
      $map['isdel'] = 1;
      if (session('regionid')){
          $map['push_addr_c|push_addr_t'] = session('regionid');
      }
    $m = new M();
    $meet = $m->where($map)->order('id desc')->paginate(2,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('meet' , $meet);
    $this->assign('page' , $meet->render());
    return $this->fetch();
  }


  public function lists($id){
    $m = new M();
    $m->where(['id'=>$id])->setInc('preview');
    $meet = $m->where(['id'=>$id])->find();
    $meet['photo'] = explode(',',$meet['photo']);
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
