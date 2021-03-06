<?php

namespace app\admin\controller;
use base\share\Safe;

class Reco extends Safe
{
    //
  public function index(){
    $listRows = 20;
    $reco = db('reco')->where('type < 4') -> order('id desc') -> paginate($listRows);
    $this -> view -> assign('reco',$reco);
    $this -> view -> assign('page',$reco -> render());
    $this -> view -> assign('count',$reco -> total());
    return view();
  }

  public function addedit(){

    if ($this->request->isPost()){
      $post_data = input('post.');
      try{

        $validate = new \think\Validate;
        $validate->rule([
          'title|网站名' => 'require|chsDash|max:100',
          'url|网址' => 'require|url',
        ]);
        if ($validate->check($post_data)) {
          $bool = db('reco')->insertGetId($post_data);
          if ($bool){
            jsonResponse(1,$bool,'成功');
          }else{
            jsonResponse(-1,'','失败');
          }
        }else{
          jsonResponse(-1,'',$validate->getError());
        }
      }catch (\Exception $e){
        jsonResponse(-1,'',$e->getMessage());
      }
    }

    return view();
  }
  
}
