<?php
namespace base\share;

use think\Controller;

class  Safe extends Controller {
    protected $user;
    protected $uId;
    // 设备变更后，应当重新登录，这里记录后面判断
  public function __construct(){
    parent::__construct();
          $this->user = session('user');
          $this->uId = session('user_id');
          $this->assign('title','都会帮');
          $this->assign('user',$this->user);

        if (!$this->user && !$this->uId){
            header('location: /admin/login');exit();
          }

        /* 后台导航*/
      $this->assign('nav',$this->Nav());

    }

    public function Nav(){

        if (Cache('NavAll'))return cache('NavAll');
        $nav_one = db('admin_group')->where(['status'=>1])->field('id,name,icon')->order('sort asc')->select();
        foreach ($nav_one as &$v){
          $v['navTwo'] = db('admin_node')->where(['group_id'=>$v['id'],'status'=>1,'isdelete'=>0])->field('name,title')->select();
        }
        Cache('NavAll',$nav_one,360);
        return Cache('NavAll');
    }

}

