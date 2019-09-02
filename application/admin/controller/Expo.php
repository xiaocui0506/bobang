<?php

//快递名称 、 快递物品名称

namespace app\admin\controller;

use base\share\Safe;
use app\index\model\Expo as E;

class Expo extends Safe
{
    //
  public function index(){
    $e = new E();
    $map = ['isdel'=>1];

    if ($this -> request -> param('expo_title')){
      $map['expo_title'] = array("like","%".$this -> request -> param('expo_title')."%");
    }
    if ($this -> request -> param('phone')){
      $map['phone'] = $this -> request -> param('phone');
    }
    if ($this -> request -> param('contacts')){
      $map['contacts'] = array("like","%".$this -> request -> param('contacts')."%");
    }
    $listRows = 20;
    $expo = $e->where($map)
      -> order('id desc')
      -> paginate($listRows, false, array('query' => $this->request->get()));
//var_dump($expo);
    $this -> view -> assign('expo',$expo);
    $this -> view -> assign('page',$expo->render());
    $this -> view -> assign('count',$expo-> count());
    return $this->view->fetch();
  }

  /* 修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $e = new E();
      $post_data = input('post.');
      $st = $post_data['st'] == 0 ? 1:0;
      $res = $e->save(['status'=>$st],['id'=>$post_data['id']]);
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
      $e = new E();
      $res = $e->save(['isdel'=>0],['id'=>$id]);
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
