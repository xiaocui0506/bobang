<?php
/**
 * Created by PhpStorm.
 * User: cui
 * Date: 2019/8/16
 * Time: 14:30
 */
namespace app\admin\controller;

use base\share\Safe;
use app\index\model\Tendering as T;

class Tendering extends Safe
{
    public function index()
    {

        $listRows = 20;
        $t = new T();
        $tendering = $t ->where(['isdel'=>1]) -> order('id desc') -> paginate($listRows, false, array('query' => $this -> request -> get()));
        $this -> view -> assign('tendering',$tendering);
        $this -> view -> assign('page',$tendering -> render());
        $this -> view -> assign('count',$tendering -> total());
        return view();
    }


  /*修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $t = new T();
      $post_data = input('post.');
      $st = $post_data['st'] == 0? 1:0;
      $res = $t->save(['status'=>$st],['id'=>$post_data['id']]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }

  /*删除数据*/
  public function del(){
    if ($this->request->isPost()){
      $t = new T();
      $post_data_id = input('post.id/d');
      $res = $t->save(['isdel'=>0],['id'=>$post_data_id]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }


}