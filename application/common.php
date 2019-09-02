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
/**
 *  分类
 * @param string $id 对应的外键
 */
if (!function_exists('GetConfigName')){
  function GetConfigName($na,$id){
    return config($na)[$id];
  }
}