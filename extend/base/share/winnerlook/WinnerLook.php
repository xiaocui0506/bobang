<?php
namespace mface\share\winnerlook;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use mface\share\winnerlook\WinnerLookHighSpeed;
 
/**
 * 云信短信服务
 * @Author: sherlock.lei@huanmedia.com
 */
class WinnerLook {
    private static $Instance;
    private $_logger;
    private $sms = '';
    private $Temples ;

    public function __construct() {
    }

    private function setConfig() {
        
        $this->_logger = new Logger ( 'SMS' );
        $file = RUNTIME_PATH . 'smslog/' . date ( "Ym" ) . '.log';
        $handler = new StreamHandler ( $file );
        $this->_logger->pushHandler ( $handler );
        
        $this->Temples = config('smsTemples');
        
    }

    public static function getInstance() {
        if (empty ( self::$Instance )) {
            self::$Instance = new self();
            self::$Instance->setConfig ();
            self::$Instance->sms = WinnerLookHighSpeed::getInstance ();
        }
        return self::$Instance;
    }

    public function sendLoginSms($mobile, $code) {
        try {
            $Temp = $this->Temples ['login'];
            $msg = str_replace ( '{$code}', $code, $Temp );
                        
            $retsms = $this->sms->sendMsg ( $msg, $mobile );
            $this->myLog ( $mobile, $msg , $retsms, 'sendLoginSms' );
        } catch ( \Exception $e ) {
            $ecode = $e->getCode () ;
            $this->myLog ( $mobile, $code, $ecode, 'sendLoginSms' );
            return $ecode ;
        }
        return $retsms;
    }

    private function myLog($mobile, $content, $return, $type = '') {
        $title =  ( $return !==0 ) ? '短信日志warm' : '短信日志info'   ;
        $this->_logger->info ( $title , [ 
                'mobile' => $mobile,
                'msg' => $content,
                'state' => $return,
                'type' => $type 
            ] );
       return true;
    }
}
