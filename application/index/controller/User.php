<?php

namespace app\index\controller;

use app\index\controller\Common;

class User extends Common
{
    public function __construct()
    {
        parent::__construct();
        if (!session('user_id')){
            header('location: /index');exit();
        }
    }

    //个人中心
    public function index(){

        return $this->fetch();
    }

    /**
     * @return object 我的发布
     * @return $t 用户查看的类型
     */
    public function release($t)
    {

        $ds = FullName($t);
        $res = db($ds['a'])->where(['user_id'=>$this->user_id,'status'=>1,'isdel'=>1])->field($ds['b'])->order('id desc')->paginate(8,false,['type'=> '\base\share\Page','var_page' => 'p']);;

//        var_dump($res);
        $this->assign('page' , $res->render());
        return $this->fetch('',['res'=>$res,'t_name'=>$ds['c'],'t_con'=>$ds['a']]);
    }

    /**
     * @return object 账号信息
     */
    public function info()
    {
        return $this->fetch();
    }

    /*删除用户发布的数据*/
    public function del(){
        if ($this->request->isPost()){
            $post_data  = input('post.');
            $res = db($post_data['t'])->where(['id'=>$post_data['id']])->update(['isdel'=>0]);
            if ($res){
                return array('status' => 1,'msg' => '成功');
            }else{
                return array('status' => -1,'msg' => '失败');
            }

        }
    }

}
