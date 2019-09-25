<?php
namespace base\share;

use base\share\GlbFunc;
use think\Session ;
USE think\Config;


/**
 * HTTP接口安全认证业务模型
 * @author vali.yang@rockhippo.cn
 * 
 */
class AuthModel {
	/**
	 * 接口安全认证的HTTP头域名字
	 * @author vali.yang@rockhippo.cn
	 */
	private $authName = 'Authentication';
	private $appid = null;       //分配置的appid
	private $account = null;     //分配的账号
	private $skey = null;        //加密验证字符串
	private $nonce = null;       //随机字符串
	private $created = null;     //时间戳
	private $session_name = 'u_preAuthen' ;  
	private $deviceid = '' ;     //设备ID
	
	 
	/**
	 * 当前实例
	 * @author vali.yang@rockhippo.cn
	 */
	protected static $_instance = null;
	
	/**
	 * 获取当前实例
	 * @author vali.yang@rockhippo.cn
	 */
	public static function getInstance() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	
	/**
	 * 检查客户端认证头域
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkAuth(){
		$reqHeader = GlbFunc::get_request_headers();
		if (! isset ( $reqHeader [$this->authName] ) || empty ( $reqHeader [$this->authName] )) {
			$rsp = jsonResponseReturn( -8100 );
			return $rsp;
		}
		$this->sData = $reqHeader [$this->authName] ;
		$authArr = explode(',', $reqHeader [$this->authName]);
		// Authentication:  appid=1, account=admin, skey=YjlhYWRmZGUzYTA5YjQ4OWFmOTQ2NGM0NmY2ZWQ5ODhiM2E4ZDZjYzcwOWEzNWVlMjY4OWU1MDk5NDZjZmFkMg==, nonce=123456, created=1430219361 , deviceid=ASFPIWFALSKFALKSADZXEalsdfasdfaffaer342s 
		
		// check appid
		$rsp = $this->checkAppId ( $authArr );
		if ($rsp !== true) return $rsp;
		// check account
		$rsp = $this->checkAccount ( $authArr );
		if ($rsp !== true) return $rsp;
		// check nonce
		$rsp = $this->checkNonce ( $authArr );
		if ($rsp !== true) return $rsp;
		// check created
		$rsp = $this->checkCreated ( $authArr );
		if ($rsp !== true) return $rsp;
		// check 动态性
		$rsp = $this->checkDynamic ( $authArr );
		if ($rsp !== true) return $rsp;
		// check skey
		$rsp = $this->checkToken( $authArr );
		if ($rsp !== true) return $rsp;
		
		$this->setDeviceid($authArr);
		$this->writeToSession ();
		return true;
	}
	
	/**
	 * 检查客户端认证头域--appid
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkAppId($authArr) {
		$str = trim ( $authArr [0] );
		$arr = explode ( '=', $str );
		
		$appids = array_keys ( Config::get('clients') );
		if ($arr [0] != 'appid' || ! in_array ( $arr [1], $appids )) {
			$rsp = jsonResponseReturn ( -8101 );
			return $rsp;
		}
        
		$this->appid = $arr [1];
		
		return true;
	}
	
	
	/**
	 * 检查客户端认证头域--account
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkAccount($authArr) {
        
		$str = trim ( $authArr [1] );
		$arr = explode ( '=', $str );
        
		$clients = Config::get('clients') ;
		
		if ($arr[0] != 'account' || $clients[$this->appid] ['account'] != $arr [1]) {
			$rsp = jsonResponseReturn( -8102 );
			return $rsp;
		}
		$this->account = $arr [1];
		return true;
	}
	
	/**
	 * 检查客户端认证头域--nonce
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkNonce($authArr) {
		$str = trim ( $authArr [3] );
		$arr = explode ( '=', $str );
		if ($arr [0] != 'nonce' || strlen ( $arr [1] ) != 6) {
			$rsp = jsonResponseReturn ( -8103 );
			return $rsp;
		}
		$this->nonce = $arr [1];
		return true;
	}
	
	/**
	 * 检查客户端认证头域--时间戳
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkCreated($authArr) {
		$str = trim ( $authArr [4] );
		$arr = explode ( '=', $str );
		if ($arr [0] != 'created' || ! $arr [1]) {
			$rsp = jsonResponseReturn (-8104 );
			return $rsp;
		}
		$ts = time ();
		// 某些用户可能故意调慢手机系统时间--超过服务器配置时则始终无法登陆
		/* 
		if ($ts - $arr [1] > $G_X ['clients'] [$this->appid] ['timeout']) {
			$rsp ['code'] = - 8105;
			$rsp ['msg'] = getMsg ( - 8105 );
			return $rsp;
		}
		 */
		
		$this->created = $arr [1];
		return true;
	}
		
	/**
	 * 检查客户端认证头域--检查动态性
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkDynamic($authArr) {
	   return true ;
	    
		$sign = $this->nonce . $this->created;
		$preAuth = Session::get( $this->session_name ); // 获取上一次请求的认证数据
		
		if ($sign == ($preAuth ['nonce'] . $preAuth ['created'])) {
			$rsp = jsonResponseReturn( - 8106 );
			return $rsp;
		}
		return true;
	}

	/**
	 * 检查客户端认证头域--检查令牌
	 * @author vali.yang@rockhippo.cn
	 */
	public function checkToken($authArr) {
		$str = trim ( $authArr [2] );
		$name = substr ( $str, 0, stripos ( $str, '=' ) );
		$token = substr ( $str, stripos ( $str, '=' ) + 1 );
		$calcToken = $this->calcToken ();
		if ($calcToken != $token) {
			$rsp = jsonResponseReturn( -8107 );
			return $rsp;
		}
		$this->skey = $token;
		return true;
	}
	
	/**
	 * 检查客户端认证头域--计算令牌
	 * @author vali.yang@rockhippo.cn
	 */
	public function calcToken() {
		$clents = Config::get('clients') ;
		$str = $clents [$this->appid] ['password'] . $this->nonce . $this->created;
		return base64_encode ( hash ( 'sha256', $str ) );
	}
	
	/**
	 * 将本次认证信息写入session
	 * @author vali.yang@rockhippo.cn
	 */
	public function writeToSession() {
		$auth = array (
				'appid' => $this->appid,
				'account' => $this->account,
				'skey' => $this->skey,
				'nonce' => $this->nonce,
				'created' => $this->created 
		);
		Session::Set ( $this->session_name, $auth);
	}
	
	/**
	 * 获取deviceid
	 * @param unknown $authArr
	 * @return string
	 */
	public function  setDeviceid($authArr){
	    $str = trim ( $authArr [5] );
	    $arr = explode ( '=', $str );
	    $deviceid = isset($arr['1']) ?  $arr['1'] : '' ;
	    $this->deviceid =  trim ( $deviceid );
	    return $this->deviceid ;
	}
	
	public function getDeviceid(){
	    return $this->deviceid ;
	}
	
	/**
	 * 获取appid
	 * @author vali.yang@rockhippo.cn
	 */
	public function getAppId(){
		return $this->appid;
	}
	
}