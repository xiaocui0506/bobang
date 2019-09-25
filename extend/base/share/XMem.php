<?php
namespace base\share;
use think\cache\driver\Memcache;
use think\cache\driver\Memcached;

if (version_compare("5.7", PHP_VERSION, ">=")) {
    class XMem extends Memcache{
        public function __construct(){
            parent::__construct();
        }
    }
}else{//7.0.0
    class XMem extends Memcached{
        public function __construct(){
            parent::__construct();
        }
    }
}

