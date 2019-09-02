<?php
/**
 * 求职
 * User: cui
 * Date: 2019/8/16
 * Time: 17:53
 */
namespace app\admin\controller;
use base\share\Safe;

use app\index\model\Jobs as J;

class Jobs extends Safe
{
  public function index()
  {

    $map = array('isdel'=>1);

    if ($this -> request -> param('job_title')){
      $map['job_title'] = array("like","%".$this -> request -> param('job_title')."%");
    }
    if ($this -> request -> param('contacts')){
      $map['contacts'] = array("like","%".$this -> request -> param('contacts')."%");
    }
    $listRows = 20;
    $j = new J();
    $jobs = $j ->where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this -> request -> get()));
    $this -> view -> assign('jobs',$jobs);
    $this -> view -> assign('page',$jobs -> render());
    $this -> view -> assign('count',$jobs -> total());
    return view();
  }


  /*修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $j = new J();
      $post_data = input('post.');
      $st = $post_data['st'] == 0? 1:0;
      $res = $j->save(['status'=>$st],['id'=>$post_data['id']]);
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
      $j = new J();
      $post_data_id = input('post.id/d');
      $res = $j->save(['isdel'=>0],['id'=>$post_data_id]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }

}