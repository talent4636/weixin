<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/1/4 12:18
 */

require_once "config.php";
require_once 'city.php';

class weather{

    private $url_pre;
    private $url_end;
    private $url;

    public function __construct(){
        if(defined('WEATHER_FROM') && WEATHER_FROM=='wthrcdn.etouch.cn'){
            $this->url = WEATHER_URL_WITH_NAME;
            $this->url_no = WEATHER_URL_WITH_NO;
        }else{
            $this->url_pre = WEATHER_URL_PRE;
            $this->url_end = WEATHER_URL_END;
        }
    }

    public function getWeather($city_name,$day=''){
        //最后用百度的api算了
        $url = "http://api.map.baidu.com/telematics/v3/weather?location=".urlencode($city_name)."&output=json&ak=XPvM6T3HCDSoM5pFC2rwhnqt";
        $result_json = file_get_contents($url);
        $result = json_decode($result_json,true);
        if($result['status'] == 'success'){//status
            $data = '';
            foreach($result['results'][0]['weather_data'] as $k => $v){
                $data .= $v['date'].'['.$v['weather'].','.$v['wind'].','.$v['temperature']."]";
                if(count($result['results'][0]['weather_data']) > $k-1){
                    if($k == 0){
                        $data .= "\n------------------------\n";
                    }else{
                        $data .= "\n";
                    }
                }

            }
            return $data;
        }else{
            error_log("\n=====".date('Y-m-d H:i:s',time())."=====\n".print_r($result),3,'./weather.log');
            return "呀，坏了，查不到！";
        }
            /*
             * //草你们妈，这些吊接口有个毛用
        if($this->url){
//            $url = $this->url.''.$city_name;
            $url = $this->url_no.city::getCityByName($city_name);
            //========================
            $fp = fopen($url, 'r');
            stream_get_meta_data($fp);
            while(!feof($fp)) {
                $result .= fgets($fp, 1024);
            }
            echo "url body: $result";
            return $result;
//            fclose($fp);
            //====================
            $msg = file_get_contents($url);
//            $msg = iconv("gb2312", "utf-8//IGNORE",$msg);
            return $msg;
        }else{
            if ($city_name=='') {
                return '城市名称不能为空，请输入城市名称! ';
            }
            $city_no = city::getCityByName($city_name);
            if (!$city_no) {
                return '您输入的城市名称找不到啊，现在我还比较笨，请重新输入吧：如【TQ上海】';
            }else{
                $url = $this->url_pre.$city_no.$this->url_end;
            }
            $msg = file_get_contents($url);
            $arrayMsg = json_decode($msg, true);
            return $arrayMsg['weatherinfo']['city'].
            '【今天】'.$arrayMsg['weatherinfo']['weather1'].'，温度：'.$arrayMsg['weatherinfo']['temp1'].','.$arrayMsg['weatherinfo']['wind1'].
            '。未来三天预报：【明天】'.$arrayMsg['weatherinfo']['weather2'].'，温度：'.$arrayMsg['weatherinfo']['temp2'].','.$arrayMsg['weatherinfo']['wind2'].
            '【后天】'.$arrayMsg['weatherinfo']['weather3'].'，温度：'.$arrayMsg['weatherinfo']['temp3'].','.$arrayMsg['weatherinfo']['wind3'].
            '【大后天】'.$arrayMsg['weatherinfo']['weather4'].'，温度：'.$arrayMsg['weatherinfo']['temp4'].','.$arrayMsg['weatherinfo']['wind4'];
        }
            */


//        if(!$day || $day==1){
//            //查询一天
//            exit($this->url_end);
//        }else {
//            //多天
//        }

        // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

        // $city_num = 101010100;

//        $s = file_get_contents($url);
//        $arrayMsg = json_decode($s, true);
//        return $arrayMsg['weatherinfo']['city'].
//        '【今天】'.$arrayMsg['weatherinfo']['weather1'].'，温度：'.$arrayMsg['weatherinfo']['temp1'].','.$arrayMsg['weatherinfo']['wind1'].
//        '。未来三天预报：【明天】'.$arrayMsg['weatherinfo']['weather2'].'，温度：'.$arrayMsg['weatherinfo']['temp2'].','.$arrayMsg['weatherinfo']['wind2'].
//        '【后天】'.$arrayMsg['weatherinfo']['weather3'].'，温度：'.$arrayMsg['weatherinfo']['temp3'].','.$arrayMsg['weatherinfo']['wind3'].
//        '【大后天】'.$arrayMsg['weatherinfo']['weather4'].'，温度：'.$arrayMsg['weatherinfo']['temp4'].','.$arrayMsg['weatherinfo']['wind4'];
    }


}

//$test = new weather();
//$xxx = $test->getWeather('上海');
////error_log(print_r($xxx,1),3,'./log.log');
//var_dump($xxx);
