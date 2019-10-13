<?php

namespace app\index\controller;

use think\Controller;

class Wsdl extends Controller
{

    //
    public $apiurl = 'http://222.143.21.205:8091/wsscservices_test/services/wsscWebService?wsdl';
    private static $soapClientHandler;

    public function setUp()
    {
        $client = $this->getSoapClientHandler();
        print "提供的方法\n";
        print_r($client->__getFunctions());
        print "相关的数据结构\n";
        print_r($client->__getTypes());
        print "\n\n";
    }

    /*测试数据*/
    public function index(){
        $abc=array("red","green","blue","yellow","brown");
        for($i=0;$i<ceil(count($abc));$i++)
        {
            echo  $i;
            $bbb[] = array_slice($abc, $i * 6 ,6);
        }
        var_dump($bbb);

    }

    /*结束*/

    /**
     * xxxxUserInfo方法
     */
    public function abc()
    {
        try {
            $parameter = array(
               'username'=>7223,'pwd'=>$this->getEncPass(),'xhbh'=>'048000100080003000100015',
            );

            $result = $this->getSoapClientHandler()->qxShByXhbh(['in0'=>json_encode($parameter)]);
//
            //调用结果返回异常
            if (!$result instanceof stdClass) {
                throw new \Exception("qxShByXhbh:" . json_encode($result));
            }


            $xml_parser = xml_parser_create();
            if (!xml_parse($xml_parser, $result->out, true)) {
                xml_parser_free($xml_parser);
                throw new \Exception("调用qxShByXhbh返回的不是一个xml结构体");
            }
            xml_parser_free($xml_parser);
            //XXE
            libxml_disable_entity_loader(true);
            $xml = simplexml_load_string($result->out, 'SimpleXMLElement', LIBXML_NOCDATA);
            // 输出参数
            var_dump($xml->data);
            echo " 成功".PHP_EOL;
        } catch (\SoapFault $soapFault) {
            throw new \Exception($soapFault->getMessage() . $this->getSoapClientHandler()->__getLastResponse());
        }
    }


    //获取商品以及参数信息
    public function goodsinfo()
    {
        try {
            $parameter = array(
                'username'=>7223,'pwd'=>$this->getEncPass(),'sprkkssj'=>'20160913094809','sprkjssj'=>date('YmdHis',time()),'pageNum'=>1,'pageSize'=>20
            );

            $result = $this->getSoapClientHandler()->findSprkandParam(['in0'=>json_encode($parameter)]);
//
            //调用结果返回异常
            if ($result instanceof stdClass) {
                throw new \Exception("findSprkandParam:" . json_encode($result));
            }
            $res = json_decode($result->out,true);
            if ($res['resultFlag'] == 'Y'){
                var_dump($res['spList']);
            }else{
                return jsonResponse(-1,'','数据获取失败');
            }
        } catch (\SoapFault $soapFault) {
            throw new \Exception($soapFault->getMessage() . $this->getSoapClientHandler()->__getLastResponse());
        }
    }

//


    /**
     * @description getSoapClientHandler
     */
    public function getSoapClientHandler()
    {
        if (!self::$soapClientHandler) {
            self::$soapClientHandler = new \SoapClient($this->getSynchApi());
        }
        return self::$soapClientHandler;
    }

    /**
     * @description getSynchApi
     */
    public function getSynchApi()
    {
        return $this->apiurl;
    }

    /**
     *  @description getEncPass
     */
    public function getEncPass(){
        try {
            $encpass = file_get_contents('http://192.168.1.211:8081/test/md5Hash/getMd5Hash?userName=7223&password=ff8080814a1353ac014a139496110049');
            $rs = json_decode($encpass, true);
            if ($rs['code'] == 1) {
                return $rs['data'];
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}
