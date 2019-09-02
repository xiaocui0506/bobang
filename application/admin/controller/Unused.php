<?php
/**
  * 闲置列表、
 */
namespace app\admin\controller;


use app\index\model\Unused as U;
use base\share\Safe;


class Unused extends Safe
{

  public function index(){

    $map = array('isdel'=>1);

    if ($this -> request -> param('unus_title')){
      $map['unus_title'] = array("like","%".$this -> request -> param('unus_title')."%");
    }
    if ($this -> request -> param('contacts')){
      $map['contacts'] = array("like","%".$this -> request -> param('contacts')."%");
    }
    $listRows = 20;
    $u = new U();
    $unused = $u ->where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this -> request -> get()));
    $this -> view -> assign('unused',$unused);
    $this -> view -> assign('page',$unused -> render());
    $this -> view -> assign('count',$unused -> total());
    return view();
  }

  /* 修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $u = new U();
      $post_data = input('post.');
      $st = $post_data['st'] == 0 ? 1:0;
      $res = $u->save(['status'=>$st],['id'=>$post_data['id']]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }

  /* 删除数据*/
  public function del(){
    $id = input('post.id/d');
    if ($id){
      $u = new U();
      $res = $u->save(['isdel'=>0],['id'=>$id]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }else{
      return array('status' => -1,'msg' => '参数错误');
    }
  }

}
