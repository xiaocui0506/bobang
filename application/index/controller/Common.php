<?php

namespace app\index\controller;



use app\index\model\Member;
use app\index\model\User;
use think\Controller;

class Common extends Controller
{
    public $user;
  //
    public function __construct()
    {
      parent::__construct();

      $this->user_id = session('user_id');
      /*判断用户是否登录*/
      $user = User::where(['id'=>$this->user_id])->field('user_name')->find();
      $this->assign('user',$user);
      /*   判断用户是否有企业身份 */
      $bool = Member::where(['user_id'=>$this->user_id])->value('id');
      $this->assign('isiden',$bool);
        /*网站公告*/
      $this->assign('notice', db('notice')->field('id,title,createtime')->order('id desc')->limit(7)->select());

      $this->assign('istime',getStrTime());

      /*内页展示头部广告图*/
        $instop = db('instop')->where(['type'=>1,'city'=>session('regionid')])->value('img');
        $this->assign('instop',$instop);

        /*内页展示底部广告图*/
        var_dump($_SERVER);
        $where = 'type = 2 AND city = '.session('regionid') .' AND (fl = 0 or fl = 2)'  ;
        $instop = db('instop')->where($where)->order('id desc')->limit(12)-> fetchSql(true)
            ->select();
        echo $instop;
        $this->assign('instop',$instop);

      if ($this->user_id){
//          招标
          $this->assign('tendering',db('tendering')->where(['user_id'=>$this->user_id])->count());
//         求购
          $this->assign('tobuy',db('tobuy')->where(['user_id'=>$this->user_id])->count());
//          会展
          $this->assign('expo',db('expo')->where(['user_id'=>$this->user_id])->count());
//          会议
          $this->assign('meeting',db('meeting')->where(['user_id'=>$this->user_id])->count());
//          招聘
          $this->assign('recruit',db('recruit')->where(['user_id'=>$this->user_id])->count());
//          求职
          $this->assign('jobs',db('jobs')->where(['user_id'=>$this->user_id])->count());
      }

//      if (!$user_id){
////        header('location: /index');exit();
////      }
    }


}
