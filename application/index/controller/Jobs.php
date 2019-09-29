<?php
/*求职 展示和发布*/

namespace app\index\controller;

use think\Cache;
use app\index\controller\Common;

use app\index\model\Jobs as J;

class Jobs extends Common
{
    //
  public function index(){
      $map['status'] = 1;
      $map['isdel'] = 1;
      if (session('regionid')){
          $map['push_addr_c|push_addr_t'] = session('regionid');
      }
    $j = new J();
    $jobs = $j->where($map)->order('id desc')->paginate(2,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('jobs' , $jobs);
    $this->assign('page' , $jobs->render());

    return $this->fetch();
  }


  public function lists($id){
      $j = new J();
      $j->where(['id'=>$id])->setInc('preview');
      $jobs = $j->where(['id'=>$id])->find();
      $this->assign('j',$jobs);
      return $this->fetch();
  }

  /*添加求职页面展示*/
  public function adds(){

    $education = config('education'); //最高学历
    $hands = config('hands'); //工作经验
    $salary = config('salary'); //期望工资
    $release_time = config('release_time'); //发布时长

    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
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
