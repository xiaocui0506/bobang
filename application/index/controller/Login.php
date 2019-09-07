<?php

namespace app\index\controller;

use app\index\model\Member;
use app\index\model\User;
use think\Cache;
use think\Controller;
use think\Request;

class Login extends Controller
{
  /**
   * 用户登录
   * @return View
   */
    public function login(){
      return $this->fetch();
    }
  /**
   * 用户登录
   * @return View
   */
  public function login_s(){
    $u = new User();
    return $u->Ulogin();
  }
  /**
   * 用户注册
   * @return View
   */
    public function reg(){
      return $this->fetch('regs');
    }

  /**
   * 用户注册数据提交
   * @return View
   */
  public function reg_s()
  {
    $u = new User();
    return $u->addreg();
  }


  /*个人认证*/
  public function percert(){
    return $this->fetch();
  }

  public function peradd(){
    if ($this->request->isPost()){
      $m = new Member;
      return $m->add();
    }
  }

  /*企业认证*/
  public function entcert(){
    return $this->fetch();
  }
  /*添加企业认证*/
  public function entadd(){
    $m = new Member();
    return $m->entadd();

  }




  //首页导航  --
  public function index(Request $request){
    if (Cache::get('nav_type')){
      jsonResponse(1,Cache::get('nav_type'),'成功');
    }
    $navall = NavAll();
    Cache::set('nav_type',$navall,360);
    jsonResponse(1,Cache::get('nav_type'),'成功');
  }


  //首页导航  --
  public function region(Request $request){
//    if (Cache::get('region')){
//      jsonResponse(1,Cache::get('region'),'成功');
//    }
    $navall = db('region')->where(['pid'=>1])->field('id,name')->select();
    foreach ($navall as $k=>&$v){
      $navall[$k]['cityList'] = db('region')->where(['pid'=>$v['id']])->field('id,name')->select();
      if ($navall[$k]['cityList']) {
        foreach ($v['cityList'] as $kk => &$vv) {
          $navall[$k]['cityList'][$kk]['areaList'] = db('region')->where(['pid' => $vv['id']])->field('name')->select();
        }
      }
    }
//    Cache::set('region',$navall,360);
  return  json($navall);
  }

}
