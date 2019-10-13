<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 公共函数
//-------------------------

use think\Config;
use think\Session;
use think\Response;
use think\Request;
use think\Url;

/**
 * CURLFILE 兼容性处理 php < 5.5
 * 一定不要修改、删除，否则 curl 可能无法上传文件
 */
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        . ($postname ?: basename($filename))
        . ($mimetype ? ";type=$mimetype" : '');
    }
}

//获取用户id
if (!function_exists('GetUserId')) {
  //    获取用户id
   function GetUserId($token){
     $res = db('user')->where(['login_token'=>$token])->field('id,type')->find();
     return $res;

   }
}


/* 导航*/
if(!function_exists('NavAll')){

  function NavAll(){
    $navall = db('admin_navs')->where(['status'=>1,'group_id'=>1])->field('id,group_id,title,url')->order('id')->select();
    foreach ($navall as $k=>&$v){
      $navall[$k]['one'] = db('admin_navs')->where(['group_id'=>$v['id']])->field('id,group_id,title,url')->order('id')->select();
      if ($navall[$k]['one']) {
        foreach ($v['one'] as $kk => &$vv) {
          $navall[$k]['one'][$kk]['two'] = db('admin_navs')->where(['group_id' => $vv['id']])->field('id,group_id,title,url')->order('id')->select();
          if ($navall[$k]['one'][$kk]['two']) {
            foreach ($navall[$k]['one'][$kk]['two'] as $kkk => &$vvv) {
              $navall[$k]['one'][$kk]['two'][$kkk]['three'] = db('admin_navs')->where(['group_id' => $vvv['id']])->field('id,group_id,title,url')->order('id')->select();
            }
          }
        }
      }
    }
    return $navall;
  }
}


/*  判断用户选择的是二级或三级*/
if (!function_exists('IsChoice')){

    function IsChoice($id){

        if (!$id) {return false;}
        $group_id_2 = db('admin_navs')->where(['id'=>$id])->value('group_id_2');
        $res = db('admin_navs')->where(['id'=>$id])->find();
        if ($group_id_2 == 0 && !$group_id_2){
            $position = db('admin_navs')->where(['id'=>$res['group_id']])->value('title').'>'.$res['title'];
//            用户选择的二级栏目
            $pid = $res['id'];
        }else{
            $position = db('admin_navs')->where(['id'=>$res['group_id_1']])->value('title').'>'.db('admin_navs')->where(['id'=>$res['group_id_2']])->value('title');
            $pid = $res['group_id_2'];
        }
            if ($pid){
                $ress = db('admin_navs')->where(['id'=>$pid])->field('id')->select();
                foreach ($ress as $k=>$v){
                    $ress[$k]['one'] = db('admin_navs')->where(['group_id'=>$pid])->field('id,title')->select();
                    if ($ress[$k]['one']){
                        foreach ($ress[$k]['one'] as $kk=>$vb){
                            $ress[$k]['one'][$kk]['two'] = db('admin_navs')->where(['group_id'=>$vb['id']])->field('id,title')->select();
                        }
                    }
                }
            }


        return ['a'=>$position,'b'=>$ress];
    }
}

/**
 * 从二维数组中取出自己要的KEY值
 * @param  array $arrData
 * @param string $key
 * @param $im true 返回逗号分隔
 * @return array
 */
function filter_value($arrData, $key, $im = false)
{
    $re = [];
    foreach ($arrData as $k => $v) {
        if (isset($v[$key])) $re[] = $v[$key];
    }
    if (!empty($re)) {
        $re = array_flip(array_flip($re));
        sort($re);
    }

    return $im ? implode(',', $re) : $re;
}

/**
 * 用户选择查看类型返回全称
 * @param $t
 * @return string
 */
if (!function_exists('FullName')){
    function FullName($t)
    {
        $a['a'] = true;
        $a['b'] = true;
        $a['c'] = true;
        switch ($t){
            case 'te':
                $a['a'] = 'tendering';
                $a['c'] = '招标发布列表';
                $a['b'] = 'id,bidding_name as title,bidding_text as text,create_time';
                break;
            case 't':
                $a['a'] = 'tobuy';
                $a['c'] = '求购发布列表';
                $a['b'] = 'id,title ,abstract as text,create_time,photo';
                break;
            case 'e':
                $a['a'] = 'expo';
                $a['c'] = '展会发布列表';
                $a['b'] = 'id,expo_title as title ,abstract as text,create_time,photo';
                break;
            case 'm':
                $a['a'] = 'meeting';
                $a['c'] = '会议发布列表';
                $a['b'] = 'id,meet_title as title ,abstract as text,create_time,photo';
                break;
            case 'r':
                $a['a'] = 'recruit';
                $a['c'] = '招聘发布列表';
                $a['b'] = 'id,title ,brief as text,create_time';
                break;
            case 'j':
                $a['a'] = 'jobs';
                $a['c'] = '求职发布列表';
                $a['b'] = 'id,job_title as title ,self_intr as text,create_time,photo';
                break;
            default:
                $a['a'] = false;
                $a['b'] = false;
        }
        return $a;
    }
}

if (!function_exists('CfPhoto')){
    function CfPhoto($photo){
        $aaa = explode(',',$photo);
         return $aaa[0];
    }
}



/**
 * 统一密码加密方式，如需变动直接修改此处
 * @param $password
 * @return string
 */
function password_hash_tp($password)
{
    return hash("md5", trim($password));
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;

    return round($size, 2) . $delimiter . $units[$i];
}


