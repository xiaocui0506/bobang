<?php
namespace base\share;

class Smstool 
{
    public function  sendSms($mobile,$code){
        try{
          $host = "https://cdcxdxjk.market.alicloudapi.com";
          $path = "/chuangxin/dxjk";
          $method = "POST";
          $appcode = "9eb9c7a26b284c6c8a5bcb53762f24ba";
          $headers = array();
          array_push($headers, "Authorization:APPCODE " . $appcode);
//          $querys = "content=【都会帮网】你的验证码是：".$code."，3分钟内有效！&mobile=$mobile";
          $querys = "content=【都会帮网】你的验证码是：".$code."，3分钟内有效！&mobile=".$mobile;
            file_put_contents('./abc.txt',$querys);
          $bodys = "";
          $url = $host . $path . "?" . $querys;
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($curl, CURLOPT_FAILONERROR, false);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HEADER, false);
          if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
          }
          $data = curl_exec($curl);
          curl_close($curl);
        }catch(\exception $e){
            $data = $e->getMessage();
        }
        return $data ;
    }    
    
    
    /**
     * 写短信发送日志
     * @param unknown $mobile
     * @param unknown $ret 发送结果
     */
    private function  writelog($mobile,$code,$ret){
        $logifle = RUNTIME_PATH.'smslog'.DS.date("Ym").'.log'; 
         
        $log = new Logger('smslog');
        $log->pushHandler(new StreamHandler( $logifle, Logger::INFO));
        $log->info('SMSLOG',['mobile'=>$mobile,'code'=>$code,'ret'=>$ret]) ;
        return true ;
    }
    
}
