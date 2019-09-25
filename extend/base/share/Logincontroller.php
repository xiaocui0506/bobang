<?php
namespace base\share;

use base\share\Safecontroller;
use app\index\model\Account;
use think\Session;

class Logincontroller extends Safecontroller {
    protected $user;
    protected $islogin = false;
    const     LOGINUSERSESSIONNAME='LOGINUSERSESSIONNAME';

    public function _initialize() {
        parent::_initialize ();
        $this->getUser ();
    }

    public function getUser() {
        if ($this->sId != '') {
            
            $sessdata = Session::get(self::LOGINUSERSESSIONNAME);
            if ( $sessdata ){
                $this->deviceCheck($sessdata->deviceid);
                
                $this->user = $sessdata;
                $this->islogin = true;
                return true ;
            }
            
            $model = new Account ();
            $ret = $model->where ( 'token', $this->sId )->find ();
            if ( $ret ) {
                if ($ret->status != 1) {  jsonResponse ( -8208 );     }
                $this->deviceCheck( $ret->deviceid );                
                $this->user = $ret;
                $this->islogin = true;
                Session::set( self::LOGINUSERSESSIONNAME,$ret );
            } else {
                jsonResponse ( -8210 );
            }
        } else {
            jsonResponse ( -8210 );
        }
    }
}

