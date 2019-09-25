<?php
namespace base\share;

class GlbFunc
{
    public static function get_request_headers() {
        foreach ( $_SERVER as $name => $value ) {
            if (substr ( $name, 0, 5 ) == 'HTTP_') {
                $headers [str_replace ( ' ', '-', ucwords ( strtolower ( str_replace ( '_', ' ', substr ( $name, 5 ) ) ) ) )] = $value;
            }
        }
        return $headers;
    }    
    
    /**
     * 对称加解密函数
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4; // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        if ( $key=='' ) {
            defined('AUTH_SYMEJNKEY') or define('AUTH_SYMEJNKEY','DCsJwf35A');
            $key = AUTH_SYMEJNKEY;
        }
        $key = md5 ( $key ? $key : AUTH_SYMEJNKEY );
        $keya = md5 ( substr ( $key, 0, 16 ) );
        $keyb = md5 ( substr ( $key, 16, 16 ) );
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
    
        $cryptkey = $keya . md5 ( $keya . $keyc );
        $key_length = strlen ( $cryptkey );
    
        $string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
        $string_length = strlen ( $string );
    
        $result = '';
        $box = range ( 0, 255 );
    
        $rndkey = array ();
        for($i = 0; $i <= 255; $i ++) {
            $rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
        }
    
        for($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }
    
        for($a = $j = $i = 0; $i < $string_length; $i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
        }
    
        if ($operation == 'DECODE') {
            if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
                return substr ( $result, 26 );
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
        }
    }
    
    public static function isAjax() {
        if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return true;
        }
        //如果参数传递的参数中有ajax
       if(!empty($_POST['ajax']) || !empty($_GET['ajax'])) {     return true;  }
       return false;
    }
    
   public  static function isPost(){
        return   strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' ;        
    }
    
    public static function isGet(){
        return   strtoupper($_SERVER['REQUEST_METHOD']) === 'GET' ;
    }
    
    public static function isMobile($num){
        if(preg_match("/^1[34578]{1}\d{9}$/", $num)){
            return true;
        }
        return false;
    }
    
}