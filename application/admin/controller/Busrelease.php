<?php
/*商家列表*/
namespace app\admin\controller;


use base\share\Safe;

use app\index\model\Busrelease as b;

class Busrelease extends Safe
{
  public function index(){
    $b = new b();

    $map = array();

    if ($this -> request -> param('corporate_name')){
      $map['corporate_name'] = array("like","%".$this -> request -> param('corporate_name')."%");
    }
    if ($this -> request -> param('legal_person')){
      $map['legal_person'] = array("like","%".$this -> request -> param('legal_person')."%");
    }
    $listRows = 20;
    $busrelease = $b
      ->where($map)
      -> order('id desc')
      -> paginate($listRows, false, array('query' => $this->request->get()));

    $this -> view -> assign('busrelease',$busrelease);
    $this -> view -> assign('page',$busrelease->render());
    $this -> view -> assign('count',$b -> count());

    return $this->view->fetch();
  }

  /*修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $b = new b();
      $post_data = input('post.');
      $st = $post_data['st'] == 0? 1:0;
      $res = $b->save(['status'=>$st],['id'=>$post_data['id']]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }

}