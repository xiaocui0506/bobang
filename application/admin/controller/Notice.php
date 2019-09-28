<?php

namespace app\admin\controller;

use app\common\model\AdminNavs;
use base\share\Safe;

class Notice extends Safe
{
    //
  public function index(){
      $map = array();

      if ($this -> request -> param('league_name')){
          $map['league_name'] = array("like","%".$this -> request -> param('league_name')."%");
      }
      if ($this -> request -> param('user_name')){
          $map['user_name'] = array("like","%".$this -> request -> param('user_name')."%");
      }
      if ($this -> request -> param('mobile')) {
          $map['mobile'] = array("like", "%" . $this->request->param('mobile') . "%");
      }

      $listRows = 12;
      $navs = db('notice') -> where($map) ->field('id,title,createtime') -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->get()));

      $this -> view -> assign('nav',$navs);
      $this -> view -> assign('page',$navs -> render());
      $this -> view -> assign("count", $navs -> total());
      return view();
  }

  public function addedit(){

      if ($this->request->isPost()){
          $post_data = input('post.data/a');
          $post_data['createtime'] = time();
          $res = db('notice')->insertGetId($post_data);
          if ($res){
              jsonResponse(1,$res,'成功');
          }else{
              jsonResponse(1,'','失败');
          }
      }
      return view();
  }

    /**
     * 删除、批量删除
     */
    public function del()
    {

        $id = input('param.id');
        $bool = db('notice')->where(['id'=>$id])->delete();
        if ($bool){
            jsonResponse(1,'','成功');
        }else{
            jsonResponse(1,'','失败');
        }
    }
}
