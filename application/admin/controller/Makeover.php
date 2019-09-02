<?php
/**
 * 公司转让列表
 * User: jokerleo
 * Date: 2018/5/3
 * Time: 15:54
 */
namespace app\admin\controller;

use base\share\Safe;

class Makeover extends Safe
{
    public function index()
    {
        $map = array('isdel'=>1);

        if ($this -> request -> param('league_name')){
            $map['name'] = array("like","%".$this -> request -> param('league_name')."%");
        }
        $m = new \app\index\model\Makeover();

        $listRows = 20;
        $makeover = $m -> where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->param()));

        $this -> view -> assign('makeover',$makeover);
        $this -> view -> assign('page',$makeover -> render());
        $this -> view -> assign("count", $makeover -> total());
        return view();
    }


  /*修改数据状态*/
  public function forbid(){
    if ($this->request->isPost()){
      $m = new \app\index\model\Makeover();
      $post_data = input('post.');
      $st = $post_data['st'] == 0? 1:0;
      $res = $m->save(['status'=>$st],['id'=>$post_data['id']]);
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
      $m = new \app\index\model\Makeover();
      $post_data_id = input('post.id/d');
      $res = $m->save(['isdel'=>0],['id'=>$post_data_id]);
      if ($res){
        return array('status' => 1,'msg' => '成功');
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }
  }








}