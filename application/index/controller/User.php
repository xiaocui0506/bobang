<?php

namespace app\index\controller;

use app\index\controller\Common;

class User extends Common
{
    //个人中心
    public function index(){
        return $this->fetch();
    }

    /**
     * @return object 我的发布
     */
    public function release()
    {
        return $this->fetch();
    }

    /**
     * @return object 账号信息
     */
    public function info()
    {
        return $this->fetch();
    }
}
