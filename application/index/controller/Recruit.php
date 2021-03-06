<?php
/*
 * 招聘列表和添加
 * */
namespace app\index\controller;

use app\index\controller\Common;

use app\index\model\Recruit as R;
use think\Cache;

class Recruit extends Common
{
    //
  public function index(){
      $map['status'] = 1;
      $map['isdel'] = 1;
      if (session('regionid')){
          $map['push_addr_c|push_addr_t'] = session('regionid');
      }
    $r = new R();
    $rec = $r->where($map)->order('id desc')->paginate(8,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('rec' , $rec);
    $this->assign('page' , $rec->render());

    return $this->fetch();
  }

  /*招聘详情*/
  public function lists($id){
    $r = new R();
    $r->where(['id'=>$id])->setInc('perview');
    $res = $r->where(['id'=>$id])->find();
    if ($res['bright']){
      $res['bright'] = Bright($res['bright']);
    }
    $this->assign('res' , $res);
    return $this->fetch();
  }

  /*添加页面*/
  public function adds(){

    $this->assign('work_na',config('work_na')); // 工作性质
    $this->assign('sala', config('salary')); // 工资范围
    $this->assign('education', config('education')); // 学历
    $this->assign('hands', config('hands')); // 学历
    $this->assign('bright', config('bright')); // 学历

    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    $this->assign('navall', $navall); // 推送行业
    return $this->fetch();
  }

  public function add(){
    $r = new R();
    return $r->add();
  }
}
