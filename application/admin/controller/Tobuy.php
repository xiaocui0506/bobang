<?php
/**
 * 求购信息
 * User: cui
 * Date: 2019/8/20
 * Time: 13:48
 */
namespace app\admin\controller;

use base\share\Safe;


class Tobuy extends Safe
{
    public function index()
    {
      $map = array('isdel'=>1);

      if ($this -> request -> param('league_name')){
        $map['name'] = array("like","%".$this -> request -> param('league_name')."%");
      }
      $t = new \app\index\model\Tobuy();

      $listRows = 20;
      $tobuy = $t -> where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->param()));

      $this -> view -> assign('tobuy',$tobuy);
      $this -> view -> assign('page',$tobuy -> render());
      $this -> view -> assign("count", $tobuy -> total());
      return view();
    }

  /*修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $t = new \app\index\model\Tobuy();
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
      $t =  new \app\index\model\Tobuy();
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