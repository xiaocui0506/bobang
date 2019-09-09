<?php
/*闲置转让*/
namespace app\index\controller;

use app\index\controller\Common;

class Unused extends Common
{
    //
  public function index(){
    $u = new \app\index\model\Unused();

    $unused = $u->where(['status'=>1,'isdel'=>1])->select();

    $this->assign('unused',$unused);

    return $this->fetch();
  }


  public function lists($id){
    $u = new \app\index\model\Unused();
    $u->where(['id'=>$id])->setInc('perview');
    $unused = $u->where(['id'=>$id])->find();
    $this->assign('unused' , $unused);
    return $this->fetch();
  }

  public function add(){
    $u = new \app\index\model\Unused();
    return $u->add();
  }
}
