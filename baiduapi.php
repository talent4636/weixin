<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/3/4 14:57
 */

/**
 * Class baiduapi
 * 支持的功能：
 * 0. 智能机器人 robot
 * 1. 天气查询
 * 2. 笑话
 * 3. 身份证查询
 * 4. 手机号码归属地查询
 * 5. 公交查询
 */
class baiduapi{

    private $_apikey;
    private $_tuling_key;
    private $_tuling_userid;

    public function __constract(){
        $this->_apikey = defined('BAIDU_API_KEY')?BAIDU_API_KEY:'5c3602f8376fce34c4ac25382454200f';
        $this->_tuling_key = defined('TULING_KEY')?TULING_KEY:'0d8b8b1451f6ad5537eef9a0ad5ba170';
        $this->_tuling_userid = defined('TULING_USER_ID')?TULING_USER_ID:'test';
    }

    //step 1
    public function getApi($apiType='robot',$info=''){
        return self::$apiType($info);
    }

    //get api result    step 3
    public function go($url,$info){
        $ch = curl_init();
        $header = array('apikey:'.$this->_apikey);
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        $res = json_decode($res,1);
        return $res;
    }

    #step 2
    //robot
    /**
     * author: ${PRODUCT_NAME}
     *   time: ${DATE} ${TIME}
     *   desc:
     * Code 说明
        100000 文本类
        200000 链接类
        302000 新闻类
        308000 菜谱类
        313000（儿童版） 儿歌类
        314000（儿童版） 诗词类
     * @result  array(
     *  'type' = 1/2, //文本/列表
     *  'text' = 'xxxxx',
     *  'list' = array(),
     * )
     */
    function robot($info){
        $url = 'http://apis.baidu.com/turing/turing/turing?key='.$this->_tuling_key.'&info='.$info.'&userid='.$this->_tuling_userid;
        $result = self::go($url,$info);
        $result_type = 1;
        switch($result['code']){
            case '100000'://文本类
                $text = $result['text'];
                break;
            case '200000'://链接类
                break;
            case '302000 '://新闻类
                break;
            case '308000'://菜谱类
                break;
            case '313000'://儿歌类
                break;
            case '314000'://诗词类
                break;
            case '40001'://参数 key 错误
            case '40002'://请求内容 info 为空
            case '40004'://当天请求次数已使用完
            case '40007'://数据格式异常
                error_log("\n===== rodot error ====\ndate:".date("Y-m-d H:i:s")."\nURL:{$url}\nresult code:".$result['code'],3,'./data/baiduapi.log');
                break;
        }
//        if()
    }

}