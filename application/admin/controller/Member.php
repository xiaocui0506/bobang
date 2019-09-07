<?php

namespace app\admin\controller;

use base\share\Safe;
use app\index\model\Member as M;

class Member extends Safe
{
    //个人会员注册
  public function index(){

    $m = new M();
    $map = ['isdel'=>1,'type'=>1];
    $percert = $m->where($map)->paginate(20);
    $this -> view -> assign('page',$percert -> render());
    $this -> view -> assign('count',$percert -> total());
   return $this->fetch('',['percert'=>$percert]);

  }


  /*修改状态*/
  public function forbid(){
    if ($this->request->isPost()){
      try{
        $post_data = input('post.');
         $st = $post_data['st'] == 1 ? 0 :1;
         $m = new M();
         $bool = $m->save(['status'=>$st],['id'=>$post_data['id']]);
         if ($bool){
           jsonResponse(1,'','成功');
         }else{
           jsonResponse(-1,'','失败');
         }
    }catch (\Exception $e){
        jsonResponse(-1,'',$e->getMessage());
      }
    }
  }

  /* 删除用户数据*/
  public function del(){
    if ($this->request->isPost()){
      try{
      $id = input('post.id/d');
      $m = new M();
      $bool = $m->save(['isdel'=>0],['id'=>$id]);
      if ($bool){
        jsonResponse(1,'','成功');
      }else{
        jsonResponse(-1,'','失败');
      }
    }catch (\Exception $e){
        jsonResponse(-1,'',$e->getMessage());
        }
    }
  }


}
