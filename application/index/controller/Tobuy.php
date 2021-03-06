<?php
/***
 * 求购信息  + 发布
 */
namespace app\index\controller;

use app\index\controller\Common;

use app\index\model\Tobuy as T;

class Tobuy extends Common
{
    //
  public function index(){

      $map['status'] = 1;
      $map['isdel'] = 1;
      if (session('regionid')){
          $map['push_addr_c|push_addr_t'] = session('regionid');
      }
    $t = new T();

    $tobuy = $t->where($map)->paginate(8,false,['type'=> '\base\share\Page','var_page' => 'p']);

    $this->assign('tobuy' , $tobuy);

      $this->assign('page' , $tobuy->render());
    return $this->fetch('lists');
  }

  public function lists($id){

    $t = new T();

    $t->where(['id'=>$id])->setInc('perview');

    $tobuy = $t->where(['id'=>$id])->find();
    $timediff = strtotime($tobuy['tobuy_deadline']) - time();

    $abc = json_decode($tobuy['pro_name'],true);
    $bbb= array();
    for($i=0;$i<ceil(count($abc));$i++)
    {
      $bbb[] = array_slice($abc, $i * 6 ,6);
    }
    $tobuy['pro_name'] = array_filter($bbb);
    $days = intval($timediff/86400);
    $this->assign('days' , $days);
    $this->assign('tobuy' , $tobuy);
    return $this->fetch('index');
  }


  public function adds(){

    $navall = \cache('nav_type');
    if (!$navall){
      cache('nav_type',NavAll(),360);
      $navall = cache('nav_type');
    }
    $this->assign('navall', $navall); // 推送行业

    $this->assign('totype',config('ToType'));

    return $this->fetch();
  }

  public function add(){
    $t = new \app\index\model\Tobuy();
    return $t->add();
  }
}
