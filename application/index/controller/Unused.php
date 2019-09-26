<?php
/*闲置转让*/
namespace app\index\controller;

use app\index\controller\Common;

class Unused extends Common
{
    //
  public function index(){
      $map['status'] = 1;
      $map['isdel'] = 1;
      if (session('regionid')){
          $map['push_addr_c|push_addr_t'] = session('regionid');
      }
    $u = new \app\index\model\Unused();

    $unused = $u->where($map)->order('id desc')->paginate(8,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('unused',$unused);
    $this->assign('page' , $unused->render());
    return $this->fetch();
  }


  public function lists($id){
    $u = new \app\index\model\Unused();
    $u->where(['id'=>$id])->setInc('perview');
    $unused = $u->where(['id'=>$id])->find();
    $unused['photo'] = explode(',',$unused['photo']);
    $this->assign('unused' , $unused);

    return $this->fetch();
  }

  public function adds(){

    $this->assign('unusedType',config('unusedType'));//闲置类型
    $this->assign('degree',config('degree'));//新旧程度

    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    $this->assign('navall', $navall); // 推送行业

    return $this->fetch();
  }

  public function add(){
    $u = new \app\index\model\Unused();
    return $u->add();
  }
}
