<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/3/6 15:22
 */

require_once 'config.php';
require_once 'baiduapi.php';
require_once 'WeixinCallbackApi.php';

if (defined("DEBUG_MODE") && !DEBUG_MODE){
    error_reporting(0);
}

if (defined("VALIDATE_MODE") && VALIDATE_MODE){
    $wechatObj = new weixinCallbackApi();
    $wechatObj->valid();
}else{
    router();
}

function router(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
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
            $baiduApi = new baiduapi();
            //todo
            $contentStr = $baiduApi->getApi('robot',$keyword);
            $msgType = "text";
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





