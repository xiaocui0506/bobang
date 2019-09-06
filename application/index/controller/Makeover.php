<?php
/*转让信息+添加*/
namespace app\index\controller;

use think\Cache;
use think\Controller;
use app\index\model\Makeover as M;
class Makeover extends Common
{
    //
  public function index(){
    var_dump(111);
  }

  public function adds(){
//  公司类型
    $cor_type = config('cor_type');
    //公司性质
    $cor_nature = config('cor_nature');
    //转让发式
    $make_mode = config('make_mode');
    /*发布时长*/
    $release_time = config('release_time');

    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    return $this->fetch('',['cor_type'=>$cor_type,'cor_nature'=>$cor_nature,'make_mode'=>$make_mode,'release_time'=>$release_time,'navall'=>$navall]);
  }

  public function add(){
    $m = new M();
    return $m->add();
  }

  public function imgs(){
    $file = $this->request->file('img');
    $abc = uploads($file);
    $q_url = '/public/uploads/'.$abc;
    $arr = ['url'=>$abc,'urs'=>$q_url];
    jsonResponse(1,$arr,'成功');
  }

}
