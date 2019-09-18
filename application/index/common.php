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
      $navall[$k]['one'] = db('admin_navs')->where(['group_id'=>$v['id']])->field('id,group_id,title,url')->select();
      if ($navall[$k]['one']) {
        foreach ($v['one'] as $kk => &$vv) {
          $navall[$k]['one'][$kk]['two'] = db('admin_navs')->where(['group_id' => $vv['id']])->field('id,group_id,title,url')->select();
        }
        foreach ($navall[$k]['one'][$kk]['two'] as $kkk => &$vvv) {
          $navall[$k]['one'][$kk]['two'][$kkk]['three'] = db('admin_navs')->where(['group_id' => $vvv['id']])->field('id,group_id,title,url')->select();
        }
      }
    }
    return $navall;
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


