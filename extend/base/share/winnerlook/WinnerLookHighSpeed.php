<?php
namespace mface\share\winnerlook;
/**
 * 高速触发短信发送类
 * @author bill.chen@huanmedia.com
 */

require_once dirname(__FILE__).'/smsfunc.php' ;


class WinnerLookHighSpeed {
    static private $_instance;
    // 相关配置参数
    // 登录名称
    protected $userCode = 'HMMM';
    // 登录密码
    protected $userPass = 'MMqwe1113';
    // 通道号
    protected $Channel = 0;
    // 域名
    protected $baseUrl = 'http://120.55.197.77:1210/Services/MsgSend.asmx/';
    // 高速触发接口错误码对照
    protected $errorCode = [ 
        '-1' => '应用程序异常',
        '-3' => '用户名密码错误或者用户无效',
        '-4' => '短信内容和备案的模板不一样',
        '-5' => '签名不正确(格式为:XXX【签名内容】), 注意：短信内容最后一个字符必须是】',
        '-7' => '余额不足',
        '-8' => '通道错误',
        '-9' => '无效号码',
        '-10' => '签名内容不符合长度',
        '-11' => '用户有效期过期',
        '-12' => '黑名单',
        '-13' => '语音验证码的Amount参数必须是整形字符串',
        '-14' => '语音验证码的内容只能为数字',
        '-15' => '语音验证码的内容最长为6位',
        '-16' => '余额请求过于频繁，5秒才能取余额一次',
        '-17' => '非法IP',
        '-18' => 'Msg格式错误',
        '-19' => '短信数量错误，小于1或者大于50',
        '-20' => '号码错误或者黑名单',
        '-21' => '没有找到对应的SubmitID设置',
        '-23' => '解密失败' 
    ];
    private function __construct() {}
    /**
     * 调用静态方法获取本类对象
     * @author bill.chen@huanmedia.com
     */
    public static function getInstance() {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }
    /**
     * 发送短信
     * @param string $msg 短信内容 
     * @param string $phone 手机号码，只能发送一个
     */
    public function sendMsg($msg,$phone) {
        $url = $this->baseUrl . 'SendMsg';
        $param = [
            'DesNo' => $phone,
            'Msg'   => $msg,
            'Channel'   => 0,
            'userCode'  => $this->userCode,
            'userPass'  => $this->userPass
        ];
        $response = fromXml(http($url,$param,'POST'));
        $retcode = $response[0] ;
        
        if ( $retcode < 0 ){
        	if  (isset($this->errorCode[$retcode])){
        		return $this->errorCode[$retcode];
        	}else{
        		return '未知错误,错误码-('.$retcode.')' ;
        	}
        }else{
        	return 0;
        }       
    }
    
    /**
     * 发送加密短信
     * @param string $msg 短信内容
     * @param string $phone 手机号码，多个号码用英文半角逗号分隔,每次提交不多于500个号码 
     */
    public function sendMsgByEncrypt($msg,$phone) {
        $url = $this->baseUrl . 'sendMsgByEncrypt';       
        $key = strtoupper(substr(sha1($this->userPass),0,8));
        $str = "userPass=".$this->userPass."&DesNo=".$phone."&Msg=".$msg."&Channel=".$this->Channel;
        $encryptstr = iconv("UTF-8","GB2312",$str);
        $encryptCode = encrypt($encryptstr,$key,$key);

        $param = ['userCode' => $this->userCode , 'submitInfo' => $encryptCode];
        $response = fromXml(http($url,$param,'POST'));
        $retcode = $response[0] ;
        
        if ( $retcode < 0 ){
        	if  (isset($this->errorCode[$retcode])){
        		return $this->errorCode[$retcode];
        	}else{
        		return '未知错误,错误码-('.$retcode.')' ;
        	}
        }else{
        	return 0 ;
        }    
    }
}
