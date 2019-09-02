<?php
namespace app\admin\controller;

use base\share\Safe;

class Index extends Safe
{
    public function index()
    {
      return $this->fetch();
    }
    public function welcome(){

      return $this->fetch();
    }
    public function lunbo(){
      return $this->fetch();
    }

    public function website(){
      $res = db('site')->where('id',1)->find();
      return $this->fetch('',['vo'=>$res]);
    }
    public function adds(){
      if ($this->request->isPost()){
          $post_data = input('post.');
          unset($post_data['file']);
          $res = \db('site')->where(['id'=>1])->update($post_data);
          if ($res){
            jsonResponse(1,0,'成功');
          }else{
            jsonResponse(-1,0,'失败');
          }

      }
    }

  public function addimg($id = 1,$t = 1){
    if ($this->request->isAjax()){
      if ($id && $t)
          $t  = $t = 1 ?'img':'backimg';
        $img = db('site')->where(['id'=>$id])->value($t);
        if ($img) {
          if (file_exists('./public/uploads/' . $img)) {
            unlink('./public/uploads/' . $img);
          }
        }
      }
      $file = $this->request->file('file');
      jsonResponse(1,uploads($file),'成功');
    }
  public function addimgs()
  {
    if ($this->request->isAjax()) {
      $file = $this->request->file('file');
      jsonResponse(1, uploads($file), '成功');
    }
  }
  public function lub(){
    if ($this->request->isPost()){
      $post_data = input('post.img/s');
      if (!$post_data)jsonResponse(-1,0,'请先上传图片');
      $res = db('site')->where(['id'=>1])->update(['lun_img'=>$post_data]);
      if ($res){
        jsonResponse(1,0,'成功');
      }else{
        jsonResponse(-1,0,'失败');
      }

    }
  }

}
