<?php

namespace app\admin\controller;

use base\share\Safe;
use think\Request;
use app\index\model\Recruit as R;

class Recruit extends Safe
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
      $r = new R;
      $listRows = 20;

      $map = array('isdel'=>1);

      $recruit = $r
        ->where($map)
      -> order('id desc')
      -> paginate($listRows, false, array('query' => $this->request->get()));

      $this -> view -> assign('recruit',$recruit);
      $this -> view -> assign('page',$recruit->render());
      $this -> view -> assign('count',$recruit -> count());
      return $this->view->fetch();
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        //
      if($request->isPost()){
        $r = new R;
        $post_data = input('post.');
        $st = $post_data['st'] == 0? 1:0;
        $bool = $r->save(['status'=>$st],['id'=>$post_data['id']]);
        if ($bool){
          return array('status' => 1,'msg' => '修改成功');
        }else{
          return array('status' => -1,'msg' => '修改失败');
        }
      }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        //
      if($request->isPost()){
        $r = new R;
        $post_data = input('post.id/d');
        $bool = $r->save(['isdel'=>0],['id'=>$post_data]);
        if ($bool){
          return array('status' => 1,'msg' => '删除成功');
        }else{
          return array('status' => -1,'msg' => '删除失败');
        }
      }
    }
}
