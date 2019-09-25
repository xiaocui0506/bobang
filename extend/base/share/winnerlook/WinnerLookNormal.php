<?php
namespace mface\share\winnerlook;
/**
 * 云信留客短信发送类
 * @author bill.chen@huanmedia.com
 */

require_once dirname(__FILE__). '/smsfunc.php';


class WinnerLookNormal {
    static private $_instance;
    // 相关配置参数
    // 登录名称
    protected $userCode = 'HMWLYX';
    // 登录密码
    protected $userPass = 'LYXabc123';
    // 通道号
    protected $Channel = 506;
    // 域名
    protected $baseUrl = 'http://112.124.24.5/api/MsgSend.asmx/';
    // 云信接口错误码对照
    protected $errorCode = [
        '-1' => '应用程序异常',
        '-3' => '用户名密码错误或者用户无效',
        '-5' => '签名不正确(格式为:XXX【签名内容】), 注意：短信内容最后一个字符必须是】',
        '-6' => '含有关键字keyWords(keyWords为敏感内容，如：-6:促销)',
        '-7' => '余额不足',
        '-8' => '没有可用的通道，或不在时间范围内',
        '-9' => '发送号码一次不能超过1000个',
        '-10' => '号码数量大于允许上限（不设置上限时，不可超过1000）',
        '-11' => '号码数量小于允许下限',
        '-12' => '模板不匹配',
        '-13' => 'Invalid Ip ip绑定用户，未绑定该ip',
        '-14' => '用户黑名单',
        '-15' => '系统黑名单',
        '-16' => '号码格式错误',
        '-17' => '无效号码（格式正常，可不是正确的电话号码，如12345456765）',
        '-18' => '没有设置用户的固定下发扩展好，不能自定义扩展',
        '-19' => '强制模板通道，不能使用个性化接口',
        '-20' => '包含非法字符',
        '-21' => '没有找到对应的submitID设置',
        '-22' => '解密失败',
        '-23' => '查询余额过频繁（至少间隔10秒）' 
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
     * @param string $phone 手机号码，多个号码用英文半角逗号分隔,每次提交不多于500个号码 
     */
    public function sendMsg($msg,$phone) {
        $url = $this->baseUrl . 'SendMsg';
        $param = [
            'DesNo' => $phone,
            'Msg'   => $msg,
            'Channel'   => $this->Channel,
            'userCode'  => $this->userCode,
            'userPass'  => $this->userPass
        ];
        $response = fromXml(http($url,$param,'POST'));
        $retcode = $response[0] ;
        if ( $retcode < 0 ){
        	if  (isset($this->errorCode[$retcode])){
        		return $this->errorCode[$retcode];
        	}else{
        		return '未知错误,错误码-'.$retcode ;
        	}
        }else{
        	return 0 ;
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
        return $response[0] < 0 ? $this->errorCode[$response[0]] : $response[0];
    }

}
