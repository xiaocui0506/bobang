<?php
/*
 * 首页导航
 * */

namespace app\admin\controller;

use base\share\Safe;
use think\Request;
use app\common\model\AdminNavs;

class Navs extends Safe
{
    //
  public function index()
  {

    $n = new AdminNavs();
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
    $navs = $n -> where($map) ->field('id,title , level , sort,status') -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->get()));

    $this -> view -> assign('nav',$navs);
    $this -> view -> assign('page',$navs -> render());
    $this -> view -> assign("count", $navs -> total());
    return view();
  }

  //  修改和添加
  public function addedit(Request $request ,$id = 0){
    $n = new AdminNavs();
    if ($request->isPost()){
      $post_data = input('post.data/a');
      if (!$post_data['one'] && !$post_data['two'] && !$post_data['three'] ){
        $group_id = $group_id_1 = $group_id_2  = $group_id_3 = 1;
        $level = 0;
      }elseif($post_data['three']){
        $group_id = $group_id_3  = $post_data['three'];
        $group_id_1 = $post_data['one'];
        $group_id_2  = $post_data['two'];
        $level = 3;
      }elseif ($post_data['two']){
        $group_id = $group_id_2 = $post_data['two'];
        $group_id_1 = $post_data['one'];
        $group_id_3 = 0;
        $level = 2;
      }else{
        $group_id = $group_id_1 = $post_data['one'];
        $group_id_2  = $group_id_3 = 0;
        $level = 1;
      }
      $data = [
        'group_id' => $group_id,
        'group_id_1' => $group_id_1,
        'group_id_2' => $group_id_2,
        'group_id_3' => $group_id_3,
        'url' => $post_data['url'],
        'title' => $post_data['title'],
        'level' => $level
      ];
      if ($post_data['id'] && $post_data['id'] != ''){
//      修改
        $res = $n->save($data,['id'=>$post_data['id']]);
        $id = $post_data['id'];
      }else{
        $data['status'] = 1;
//        添加
//        三个分类都为空时  添加数据为一级
        $res = $n->save($data);
        $id = $n->id;
      }
      if ($res){
        return array('status' => 1,'data'=>$id,'msg' => '成功','url' => url('Navs/index'));
      }else{
        return array('status' => -1,'msg' => '失败');
      }
    }else{
      $navs_edit = ['group_id_1'=>0,'group_id_2'=>0,'group_id_3'=>0,];
      if ($id > 0){
        $navs_edit = $n::where(['id'=>$id])->field('id,title,group_id_1,url,group_id_2,group_id_3')->find();
      }
      $this -> view -> assign("navedit", $navs_edit);
    // 展示一级分类
      $navs_data = $n::where(['group_id'=>1])->field('id,title')->select();
      $this -> view -> assign("navs_data", $navs_data);
    }

    return view();
  }



  /*ajax 获取分类信息*/
  public function getTypes(Request $request){
    if ($request->isPost()){
      $n = new AdminNavs();
      $post_data = input('post.');

      $navs_data = $n::where(['group_id'=>$post_data['id']])->field('id,title')->select();
      $html = ' <option value="0">请选择分类</option>';
      foreach ($navs_data as $v){
        if ($post_data['fs']>0 && $post_data['fs']>1) {
          if ($post_data['fs'] = $v['id']) {
            $html .= '<option value="' . $v['id'] . '"  selected  >' . $v['title'] . '</option>';
          }else{
            $html.= '<option value="'.$v['id'].'"    >'.$v['title'].'</option>';
          }
        }else{
          $html.= '<option value="'.$v['id'].'"    >'.$v['title'].'</option>';
        }
      }
      if ($html)
        return ['status'=>1,'data'=>$html,'msg'=>'成功'];
      else
        return ['status'=>1,'data'=>$html,'msg'=>'失败'];
    }
  }



  /**
   * 禁用、批量禁用
   */
  public function forbid()
  {
    $n = new AdminNavs();
    $id = input('param.id');
    $where['id'] = array('in',$id);
    $data['status'] = 0;
    $res = $n -> save($data,$where);
    if ($res){
      return array('status' => 1,'msg' => '禁用成功');
    }else{
      return array('status' => -1,'msg' => '禁用失败');
    }
  }

  /**
   * 恢复、批量恢复
   */
  public function resume()
  {
    $n = new AdminNavs();
    $id = input('param.id');
    $where['id'] = array('in',$id);
    $data['status'] = 1;
    $res = $n -> save($data,$where);
    if ($res){
      return array('status' => 1,'msg' => '恢复成功');
    }else{
      return array('status' => -1,'msg' => '恢复失败');
    }

  }

  /**
   * 删除、批量删除
   */
  public function del()
  {
    $n = new AdminNavs();
    $id = input('param.id');
    $bool = $n->where(['group_id'=>$id])->field('id')->find();
    if ($bool){ return array('status' => -1,'msg' => '请查看是否有下级为删除');}
    $res = $n -> where(['id'=>$id]) -> delete();
    if ($res){
      return array('status' => 1,'msg' => '删除成功');
    }else{
      return array('status' => -1,'msg' => '删除失败');
    }
  }


}
