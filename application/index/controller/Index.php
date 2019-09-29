<?php
namespace app\index\controller;
use app\index\controller\Common;
use base\share\Smstool;
use think\Session;

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
            $user = new \app\index\model\User();
            if ($user->where(['mobile'=>$tel])->value('id'))jsonResponse(0,'','手机号存在，请更换或申诉..');
          $num = rand(100000,999999);
          cookie($tel,$num,180);
          $sm = new Smstool();
          $rl = json_decode($sm->sendSms($tel,$num),true);
          if ($rl['ReturnStatus'] == 'Success' && $rl['Message'] == 'ok'){
              jsonResponse(1,$tel,'发送成功');
          }else{
              jsonResponse(0,'','发送失败');
          }
        }else{
            jsonResponse(0,'','请填写手机号');
        }
      }
  }



  /* 商品的二级页面*/
    public function lists($id){

        /* 面包屑链接*/
        $res = IsChoice($id);
        /*当前地区展示*/
        $pid = db('region')->where(['id'=>session('regionid')])->value('pid');
        $reds = db('region')->where(['id'=>$pid])->field('id,pid')->find();
        if ($reds['pid'] == 1){
//        说明用户选择了市级
            $ids = session('regionid');
        }else{
           $ids = $reds['id'];
        }
        $resd = db('region')->where(['pid'=>$ids])->field('id,pid,name')->select();
        return $this->fetch('',['res'=>$res,'resd'=>$resd]);
    }


    /*地区选择页面*/
    public function region(){
        $this->assign('region',Regions());
        return $this->fetch();
    }

    public function resd(){
        if ($this->request->isPost()){
            $id = input('post.id/d');
            if (!$id){return false;}
            session('regionid',$id);
        }
    }


    public function adds(){
        if (\session('user_id')){
            return $this->fetch();
        }

    }


    /*网站公告*/
    public function notice($id){
        db('notice')->where(['id'=>$id])->setInc('view');
        $this->assign('noti',db('notice')->where(['id'=>$id])->find());
        return $this->fetch();
    }



    /* 用户准备购买广告位页面*/
    public function buy(){
        return $this->fetch();
    }
    public function buyadd(){
        if ($this->request->isPost()){
            $post_data = input('post.');
            var_dump($post_data);
        }
    }


    /**
     * @return object 用户退出
     */
    public function quit()
    {
        \session(null);
        header('location: /index');exit();
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
