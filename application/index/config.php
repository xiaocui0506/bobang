<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

use \think\Request;

$basename = Request::instance()->root();
if (pathinfo($basename, PATHINFO_EXTENSION) == 'php') {
    $basename = dirname($basename);
}

return [
    // 模板参数替换
    'view_replace_str' => [
        '__ROOT__'   => $basename,
      '__LIB__'    => $basename . '/static/admin/lib',
        '__USER__'   => $basename . '/static/user'
    ],


    // 异常处理 handle 类 留空使用 \think\exception\Handle
      'exception_handle' => '\\TpException',


];
