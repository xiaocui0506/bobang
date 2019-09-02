<?php
/**
 * Created by PhpStorm.
 * User: jokerleo
 * Date: 2018/6/11
 * Time: 9:06
 */
namespace app\index\controller;

use think\Controller;
use think\Db;

class Check extends Controller
{
    public function index()
    {
        file_put_contents("/www/wwwroot/yz.kuai8.com.cn/runtime/log/check_pay/".date("Y-m-d",time())."log.txt","触发成功".time(),FILE_APPEND);

        $expire_time = Db::name('League') -> where(array('status' => 1,'state' => 1)) -> column('id,expire_time');
        foreach ($expire_time as $v) {
            if (time() > $v['expire_time']){
                $data1 = array();
                $data1['expire_status'] = 2;

                Db::name('League')->where(array('id' => $v['id']))->update($data1);
            }
        }
    }
}