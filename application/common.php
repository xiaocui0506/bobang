<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Db;

function jsonResponse($code = 0, $data = [], $msg = '')
{
  $return = jsonResponseReturn($code, $data, $msg);
  if (empty($return['data'])) {
    $json = json_encode($return, JSON_FORCE_OBJECT);
  } else {
    $json = json_encode($return, JSON_UNESCAPED_UNICODE);
  }
  header('Content-type:application/json;charset=utf-8');
  echo $json;
  exit ();
}


function jsonResponseReturn($code = 0, $data = [], $msg = '') {
  $msg = empty ( $msg ) ? getMsg ( $code ) : $msg;
  $return = [
    'code' => $code,
    'data' => $data,
    'msg' => $msg
  ];
  return $return;
}

// 应用公共文件
function getMsg($code) {
  $code = intval($code) ;
  $msg = config( 'msg' );
  if (isset ( $msg [$code] )) {
    $ret = $msg [$code];
    return $ret;
  }
  return 'UnknowError';
}
function msg($code){
  $msg = '';
  switch ($code)
  {
    case 1:
      $msg = '登录成功';
      break;
    default:
      $msg = '非法登录';
  }
  return $msg;
}

/**
 *  分类
 * @param string $id 对应的外键
 */
if (!function_exists('GetAdminNavs')){
  function GetAdminNavs($id){
    return db('admin_navs')->where(['id'=>$id])->value('title');
  }
}
/**
 *  读取用户指定的地区名称
 * @param string $id 对应的外键
 */
if (!function_exists('GetRegionName')){
  function GetRegionName($region){
    $res = db('region')->where('id','in',$region)->field('name')->select();
    $names = '';
    foreach ($res as $v){
      $names .= $v['name'].',';
    }
    return substr($names,0,-1);
  }
}

if (!function_exists('GetRegionOneName')){
    function GetRegionOneName($id){
        return db('region')->where(['id'=>$id])->value('name');
    }
}
/**
 *  分类
 * @param string $id 对应的外键
 */
if (!function_exists('GetConfigName')){
  function GetConfigName($na,$id){
    return config($na)[$id];
  }
}

if (!function_exists('getStrTime')) {
  function getStrTime()
  {
    $no = date("H", time());
    if ($no > 0 && $no <= 6) {
      return "凌晨好";
    }
    if ($no > 6 && $no < 12) {
      return "上午好";
    }
    if ($no >= 12 && $no <= 18) {
      return "下午好";
    }
    if ($no > 18 && $no <= 24) {
      return "晚上好";
    }
    return "您好";
  }

}
/**
 * base64 图片解码
 * @param  string $base64_image_content base64图片（字符串）
 * @param  string $path 图片存放的路径
 */
function base64_image_content($base64_image_content,$path){
  //匹配出图片的格式
  if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
    $type = $result[2];
    $new_file = $path."/".date('Ymd',time())."/";
    if(!file_exists($new_file)){
      //检查是否有该文件夹，如果没有就创建，并给予最高权限
      mkdir($new_file, 0700);
    }
    $new_file = $new_file.time().".{$type}";
    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
      return '/'.$new_file;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

if (!function_exists('uploads')) {
  function uploads($file)
  {
    // 移动到框架应用根目录/public/uploads/ 目录下
    if ($file) {
      $info = $file->move('public/uploads');
      if ($info) {

        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//      echo $info->getSaveName();
        return str_replace('\\', '/', $info->getSaveName());
      } else {
        // 上传失败获取错误信息
        return $file->getError();
      }
    }
  }
}

/**
 * 去除不需要查询的字段
 * @$tablename 表名
 * @param  array  $arr  不需要的字段
 *
 */
if (!function_exists('GetFieldRemove')) {
  function GetFieldRemove($tablename,$arr)
  {
    $tableInfo = Db::getTableInfo($tablename);
    $array = array_merge($tableInfo['fields'],$arr);
    $a = array_diff($array,$arr);
    return implode(',',$a);
  }
}


/**
  * 获取亮点
  * string $b  用户选择的亮点id
 **/
if (!function_exists('Bright')){
  function Bright($b){
    $bright = config('bright');
    $b = explode(',',$b);
    $arr = [];
    foreach ($bright as $k=>$v){
      foreach ($b as $vs){
        if ($vs == $k){
          $arr[] = $v;
        }
      }
    }
    return $arr;
  }
}


if (!function_exists("Regions")){

    //首页导航  --
     function Regions()
     {
         if (cache('region')) {
             return cache('region');
         }
         $navall = db('region')->where(['pid' => 1])->field('id,name')->select();
         foreach ($navall as $k => &$v) {
             $navall[$k]['cityList'] = db('region')->where(['pid' => $v['id']])->field('id,name')->select();
             if ($navall[$k]['cityList']) {
                 foreach ($v['cityList'] as $kk => &$vv) {
                     $navall[$k]['cityList'][$kk]['areaList'] = db('region')->where(['pid' => $vv['id']])->field('id,name')->select();
                 }
             }
         }
         cache('region', $navall, 360);
         return cache('region');
     }
}