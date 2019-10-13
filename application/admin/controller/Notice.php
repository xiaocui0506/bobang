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
    /**
     * 删除、批量删除
     */
    public function dels()
    {
        $id = input('param.id');
        $img = db('instop')->where(['id'=>$id])->value('img');
        unlink('/public/uploads/'.$img);
        $bool = db('instop')->where(['id'=>$id])->delete();
        if ($bool){
            jsonResponse(1,'','成功');
        }else{
            jsonResponse(1,'','失败');
        }
    }

    /*内页广告位 顶部*/
    public function instopimg(){
        $map = array('type'=>1);
        $listRows = 12;
        $navs = db('instop') -> where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->get()));
        $this -> view -> assign('instop',$navs);
        $this -> view -> assign('page',$navs -> render());
        $this -> view -> assign("count", $navs -> total());
        return view();
    }

    /*添加页面图片*/
    public function instopadd(){
        $region = db('region')->where(['pid' => 1])->field('id,name')->select();
        $this -> view -> assign('region',$region);
        return view();
    }
    public function instopedit(){
        if ($this->request->isPost()){
            $post_data = input('post.data/a');
            if ($post_data['one'] && $post_data['two'] ){
                if ($post_data['imgs']){
                    unset($post_data['id']);
                    $data = [
                        'province' => $post_data['one'],
                        'city' => $post_data['two'],
                        'img' => $post_data['imgs'],
                        'type' => 1,
                        'createtime' => time(),
                    ];
                    $bool = db('instop')->insertGetId($data);
                    if($bool){
                        return ['status'=>1,'data'=>$bool,'msg'=>'添加成功'];
                    }else{
                        return ['status'=>1,'data'=>'','msg'=>'添加失败'];
                    }
                }else{
                    jsonResponse(-1,'','请选择图片');
                }
            }else{
                jsonResponse(-1,'','请选择地区');
            }
        }
    }

    public function getRegion(){
        $post_data = input('post.');
        $region = db('region')->where(['pid' => $post_data['id']])->field('id,name')->select();
        $html = ' <option value="0">请选择分类</option>';
        foreach ($region as $v){
            $html.= '<option value="'.$v['id'].'"  >'.$v['name'].'</option>';
        }
        if ($html)
            return ['status'=>1,'data'=>$html,'msg'=>'成功'];
        else
            return ['status'=>1,'data'=>$html,'msg'=>'失败'];
    }


    public function getImage(){
        $file = $this->request->file('file');
        $res = uploads($file);
        jsonResponse(1,$res,'成功');
    }



    /*内页底部图片*/
    public function insbot(){
        $map = array('type'=>2);
        $listRows = 12;
        $navs = db('instop') -> where($map) -> order('id desc') -> paginate($listRows, false, array('query' => $this->request->get()));
        $this -> view -> assign('instop',$navs);
        $this -> view -> assign('page',$navs -> render());
        $this -> view -> assign("count", $navs -> total());
        return view();
    }
    /*添加页面图片*/
    public function insbotadd(){
        $region = db('region')->where(['pid' => 1])->field('id,name')->select();
        $this -> view -> assign('region',$region);
        return view();
    }
    public function insbotedit(){
        if ($this->request->isPost()){
            $post_data = input('post.data/a');
            if ($post_data['one'] && $post_data['two'] ){
                if ($post_data['imgs']){
                    unset($post_data['id']);
                    $data = [
                        'province' => $post_data['one'],
                        'city' => $post_data['two'],
                        'img' => $post_data['imgs'],
                        'fl'  =>$post_data['fl'],
                        'title'  =>$post_data['title'],
                        'type' => 2,
                        'createtime' => time(),
                    ];
                    $bool = db('instop')->insertGetId($data);
                    if($bool){
                        return ['status'=>1,'data'=>$bool,'msg'=>'添加成功'];
                    }else{
                        return ['status'=>1,'data'=>'','msg'=>'添加失败'];
                    }
                }else{
                    jsonResponse(-1,'','请选择图片');
                }
            }else{
                jsonResponse(-1,'','请选择地区');
            }
        }
    }



}
