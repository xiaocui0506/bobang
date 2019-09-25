<?php
namespace mface\share\winnerlook;
/**
 * http请求
 * @param string $url 请求地址
 * @param string $param 参数
 * @param int $action GET|POST 请求类型
 * @author bill.chen@huanmedia.com
 */
function http($url, $param = [], $action = "GET") {
    $ch = curl_init ();
    $config = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url
    ];
    if ($action == "POST") {
        $config [CURLOPT_POST] = true;
    }
    $config [CURLOPT_POSTFIELDS] = http_build_query ( $param );
    curl_setopt_array ( $ch, $config );
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    return $result;
}
/**
 * 将xml转为array
 * @param string $xml
 * @author bill.chen@huanmedia.com
 */
function fromXml($xml) {
    if(!$xml) throw new WxPayException("xml数据异常！");
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}
/**
 * 加密算法相关
 */
function encrypt($str,$key,$iv) {
//加密，返回大写十六进制字符串
	$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
	$str = pkcs5Pad ( $str, $size );
	return strtoupper( bin2hex( mcrypt_cbc(MCRYPT_DES, $key, $str, MCRYPT_ENCRYPT, $iv ) ) );
}

function decrypt($str,$key,$iv) {
//解密
	$strBin = hexTobin( strtolower( $str ) );
	$str = mcrypt_cbc( MCRYPT_DES, $key, $strBin, MCRYPT_DECRYPT, $iv );
	$str = pkcs5Unpad( $str );
	return $str;
}

function hexTobin($hexData) {
	$binData = "";
	for($i = 0; $i < strlen ( $hexData ); $i += 2) {
		$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
	}
	return $binData;
}

function pkcs5Pad($text, $blocksize) {
	$pad = $blocksize - (strlen ( $text ) % $blocksize);
	return $text . str_repeat ( chr ( $pad ), $pad );
}

function pkcs5Unpad($text) {
	$pad = ord ( $text {strlen ( $text ) - 1} );
	if ($pad > strlen ( $text ))
		return false;
	if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
		return false;
	return substr ( $text, 0, - 1 * $pad );
}
	