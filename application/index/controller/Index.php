<?php
namespace app\index\controller;

use app\index\controller\Common;

class Index extends Common
{
    public function index()
    {
      $_SERVER['REDIRECT_URL'] = 'index/index';



      return $this->fetch();

    }


    /**
     * 短信验证
     */
    public function yz_code()
    {
        $tel = input('param.mobile');
        if (empty($tel)){
            return array('status' => -1,'msg' => '请填写电话');
        }
        if (!preg_match("/1[3458]{1}\d{9}$/", $tel)){
            return array('status' => -1,'msg' => '电话格式不对');
        }
        $mobile = db('admin_user') -> where(array('account' => $tel)) -> find();
        if (!empty($mobile)){
            return array('status' => -1,'msg' => '该电话已被注册');
        }
        $data = sms_code($tel);

        if (!empty($data)){
            return array('status' => 1,'msg' => '短信已发送请注意查收');
        }else{
            return array('status' => -1,'msg' => '请求失败');
        }
    }


    /*
     * 下载的的方法
     *
     *
     * */

    public  function  uploadApp() {
        echo   '正在下载';
        $this->redirect("http://".$_SERVER["HTTP_HOST"]."/download.apk");
        //header("Location:http://yz.kuai8.com.cn/download.apk");

    }




}
