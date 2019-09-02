<?php
/*求职 展示和发布*/

namespace app\index\controller;

use think\Cache;
use think\Controller;

use app\index\model\Jobs as J;

class Jobs extends Common
{
    //
  public function index(){
    echo date("Y-m-d",1566547200);
//    echo date("Y-m-d",1566608977);
    echo date("Y-m-d",1567756800);
    return $this->fetch();
  }

  /*添加求职页面展示*/
  public function adds(){

    $education = config('education'); //最高学历
    $hands = config('hands'); //工作经验
    $salary = config('salary'); //期望工资
    $release_time = config('release_time'); //发布时长

    $navall = Cache::get('nav_type');
    if (!$navall){
       Cache::set('nav_type',NavAll(),360);
      $navall = Cache::get('nav_type');
    }
    return $this->fetch('',['education'=>$education,'hands'=>$hands,'salary'=>$salary,'release_time'=>$release_time,'navall'=>$navall]);
  }

  public function add(){
    $j = new J();
    return $j->add();
  }

  public function imgs(){
    $file = $this->request->file('img');
    $abc = uploads($file);
    $q_url = '/public/tmp/uploads/'.$abc;
    $arr = ['url'=>$abc,'urs'=>$q_url];
    jsonResponse(1,$arr,'成功');
  }
}
