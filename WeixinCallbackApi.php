<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/1/4 11:04
 */
require_once "city.php";
require_once "model.php";

class weixinCallbackApi
{
//    public function __construct() {
//    }

    //验证用的，现在没用了 -_-\\
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    //返回信息函数
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";
            if(!empty( $keyword )){
                $contentStr = $this->_checkMsgMain($keyword);
                $msgType = "text";
                // $contentStr = $retuenMsg;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "欢迎您关注我们的公众账号，我在努力学习，目前可以提供“天气预报”和“在线翻译”功能，使用方法是：发送如：【TQ上海】查询上海未来3天天气，【FY你好你好】翻译“你好你好”。试试看吧！";
            }

        }else {
            echo "欢迎您关注我们的公众账号，我在努力学习，目前可以提供“天气预报”和“在线翻译”功能，使用方法是：发送如：【TQ上海】查询上海未来3天天气，【FY你好你好】翻译“你好你好”。试试看吧！";
            exit;
        }
    }

    //这个方法在申请开发者的时候验证用一下，然后就不用了
    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    //这里是处理请求文字的核心函数  by xiejun
    private function _checkMsgMain($keyword){
        // return $keyword;
        /*第一步，识别keyword中的关键字 ：
          FY--翻译
          TQ--天气预报
         */
        $keyword = trim($keyword);
        if ($keyword == '笑话'){
            return $this->getJoke();
        }
        $keyMethod = substr($keyword, 0,2);
        $content = trim(substr($keyword, 2));
        switch ($keyMethod) {
            case 'FY':case 'fy':
            $answerMsg = $this->getFY($content);
            // $answerMsg = '你想翻译的内容是：'.$content;
            break;
            case 'TQ':case 'tq':
                $weather = new weather();
                $answerMsg = $weather->getWeather($content);
//            $answerMsg = $this->getTQYB($content);
            // $answerMsg = '你想查看天气预报的城市是：'.$content;
            break;
                case 'XH':case 'xh':
                $answerMsg = $this->getJoke();
                break;
            default:
                $answerMsg = '欢迎!! 我们目前可以提供“天气预报”、“在线翻译”和“讲笑话”功能。发送如：【TQ上海】查询上海未来3天天气，【FY你好你好】翻译“你好你好”，【笑话】获取一条小笑话。试试看吧！^_^';
                break;
        }
        return $answerMsg;
    }

    public function getFY($content){
        $url = 'http://openapi.baidu.com/public/2.0/bmt/translate?client_id=ep6NCmBSGLSIOVCHfwEnAxjD&q='.$content.'&from=auto&to=auto';
        $answerMsgArr = get_object_vars(json_decode(file_get_contents($url)));
        $translateArr = get_object_vars($answerMsgArr['trans_result'][0]);
        return $translateArr['src'].'：'.$translateArr['dst'];
        // return $answerMsgArr['trans_result'][0]['src'];
    }

    public function getJoke(){
        $mdlJoke = new db();
        $jokeInfo = $mdlJoke->getRow();
        $answerMsg = "[{$jokeInfo['joke_type']}]".iconv("GB2312","UTF-8",$jokeInfo['joke_content']);
        return $answerMsg;
    }


}

//test case
//$xx = new weixinCallbackApi();
//print_r($xx->getJoke());