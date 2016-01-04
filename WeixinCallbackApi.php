<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/1/4 11:04
 */

class weixinCallbackApi
{
#    public $joke;

    public function __construct() {
#        $this->joke = new joke();
    }

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
            $answerMsg = $this->getJoke();
            return $answerMsg;
        }
        $keyMethod = substr($keyword, 0,2);
        $content = trim(substr($keyword, 2));
        switch ($keyMethod) {
            case 'FY':case 'fy':
            $answerMsg = $this->getFY($content);
            // $answerMsg = '你想翻译的内容是：'.$content;
            break;
            case 'TQ':case 'tq':
            $answerMsg = $this->getTQYB($content);
            // $answerMsg = '你想查看天气预报的城市是：'.$content;
            break;
            //            case 'XH':case 'xh':
            //            $answerMsg = $this->getJoke();
            //            break;
            default:
                $answerMsg = '欢迎您关注我们的公众账号，目前可以提供“天气预报”、“在线翻译”和“讲笑话”功能，使用方法是：发送如：【TQ上海】查询上海未来3天天气，【FY你好你好】翻译“你好你好”，【笑话】获取一条小笑话。试试看吧！^_^';
                break;
        }
        return $answerMsg;
    }

    //拼接返回值的函数
    public function getTQYB($content){
        // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        if ($content=='') {
            return '城市名称不能为空，请输入城市名称! ';
        }
        // $city_num = 101010100;
        $url = 'http://m.weather.com.cn/data/'.$this->_getCityNum($content).'.html';
        if (!$this->_getCityNum($content)) {
            return '您输入的城市名称找不到啊，现在我还比较笨，请重新输入吧：如【TQ上海】';
        }
        $s = file_get_contents($url);
        $arrayMsg = json_decode($s, true);
        return $arrayMsg['weatherinfo']['city'].
        '【今天】'.$arrayMsg['weatherinfo']['weather1'].'，温度：'.$arrayMsg['weatherinfo']['temp1'].','.$arrayMsg['weatherinfo']['wind1'].
        '。未来三天预报：【明天】'.$arrayMsg['weatherinfo']['weather2'].'，温度：'.$arrayMsg['weatherinfo']['temp2'].','.$arrayMsg['weatherinfo']['wind2'].
        '【后天】'.$arrayMsg['weatherinfo']['weather3'].'，温度：'.$arrayMsg['weatherinfo']['temp3'].','.$arrayMsg['weatherinfo']['wind3'].
        '【大后天】'.$arrayMsg['weatherinfo']['weather4'].'，温度：'.$arrayMsg['weatherinfo']['temp4'].','.$arrayMsg['weatherinfo']['wind4'];
    }

    public function getFY($content){
        $url = 'http://openapi.baidu.com/public/2.0/bmt/translate?client_id=ep6NCmBSGLSIOVCHfwEnAxjD&q='.$content.'&from=auto&to=auto';
        $answerMsgArr = get_object_vars(json_decode(file_get_contents($url)));
        $translateArr = get_object_vars($answerMsgArr['trans_result'][0]);
        return $translateArr['src'].'：'.$translateArr['dst'];
        // return $answerMsgArr['trans_result'][0]['src'];
    }

    //    public function getJoke(){
    //        $jokeinfo = $this->joke->getRundomSingleJoke();
    ////         return $jokeinfo;
    ////         $title=$jokeinfo[0]['title'];
    ////         $content=$jokeinfo[0]['content'];
    ////         $title=iconv("ASCII","GBK", $jokeinfo[0]['title']);
    ////         $content=iconv("ASCII", "UTF-8", $jokeinfo[0]['content']);
    //        return "【".$jokeinfo[0]['title']."】".$jokeinfo[0]['content'];
    //    }


}