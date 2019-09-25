<?php
namespace app\index\controller;
use app\index\controller\Common;
use base\share\Smstool;

class Index extends Common
{
    public function index()
    {
      $_SERVER['REDIRECT_URL'] = 'index/index';

      $navall = \cache('nav_type');
      if (!$navall){
        cache('nav_type',NavAll(),360);
        $navall = cache('nav_type');
      }

      //网址推荐
      $reco_web['isreco'] = db('reco')->where(['isreco'=>1,'type'=>['in','1,2,3']])->limit(18)->select();
      //
      $reco_web['a'] = db('reco')->where(['type'=>1])->limit(18)->select();
      //
      $reco_web['b'] = db('reco')->where(['type'=>2])->limit(18)->select();
      //
      $reco_web['c'] = db('reco')->where(['type'=>3])->limit(18)->select();

      return $this->fetch('',['navall'=>$navall,'reco_web'=>$reco_web]);

    }

  public function smscode(){
      if ($this->request->isPost()){
        $tel = input('post.tel/s');
        if ($tel){
          $num = rand(100000,999999);
          session($tel,$num);
          $sm = new Smstool();
          return $sm->sendSms($tel,$num);
        }
      }
  }



  /* 商品的二级页面*/
    public function lists($id){

        /* 面包屑链接*/
        $res = IsChoice($id);

        return $this->fetch('',['res'=>$res]);
    }




    public function adds(){

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


    public function ceshi(){;
        $wsdl='http://222.143.21.205:8091/wsscservices_test/services/wsscWebService?wsdl';

        $client = new \SoapClient($wsdl);
        print "提供的方法\n";
        print_r($client->__getFunctions());
        print "相关的数据结构\n";
        print_r($client->__getTypes());
        print "\n\n";

    }



}
