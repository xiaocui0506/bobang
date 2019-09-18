<?php
/*
 *
 *    添加招标发布
 * */
namespace app\index\controller;
use app\index\model\Tendering as T;

class Tendering extends Common
{
  /*  招标页面 列表 */
  public function index(){

    /* 企业招标列表信息*/
    $t = new T();
    $tend = db('tendering')->where(['status'=>1,'isdel'=>1])->select();
//var_dump($tend);
    $this->assign('tend' , $tend);

    return $this->fetch('list');
  }

  /*  招标页面 列表 */
  public function lists($id){

    $t = new T();
    $t->where(['id'=>$id])->setInc('preview');
    $tend = $t->where(['id'=>$id])->find();
    $abc = json_decode($tend['bidding_pro'],true);
    $bbb= array();
    for($i=0;$i<ceil(count($abc));$i++)
    {
      $bbb[] = array_slice($abc, $i * 6 ,6);
    }
    $tend['bidding_pro'] = array_filter($bbb);
    $timediff = strtotime($tend['bidding_deadline']) - time();
    $days = intval($timediff/86400);
    $this->assign('tend' , $tend);
    $this->assign('days' , $days);

    return $this->fetch('index');
  }


  public function adds(){


    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    $this->assign('navall', $navall); // 推送行业

    $this->assign('tendtype',config('tendtype'));

    return $this->fetch();
  }

  /* 招标 添加数据*/
  public function add(){
    $t = new T();
    return $t->add();

  }
}
