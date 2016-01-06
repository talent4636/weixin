<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/1/4 11:04
 */

//TOKEN，微信后台维护
define("TOKEN", "xiejun123");

//是否验证模式
define("VALIDATE_MODE",false);

//debug模式
define("DEBUG_MODE", false);

//可选  weather.com  wthrcdn.etouch.cn
define('WEATHER_FROM', 'wthrcdn.etouch.cn');
//weather url [from wather.com]
define('WEATHER_URL_PRE', 'http://www.weather.com.cn/adat/sk/');
define('WEATHER_URL_END', '.html');
//weather url [from wthrcdn.etouch.cn]
define('WEATHER_URL_WITH_NAME','http://wthrcdn.etouch.cn/weather_mini?city=');
define('WEATHER_URL_WITH_NO','http://wthrcdn.etouch.cn/weather_mini?citykey=');

//database config  -- mysql
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'weixin');
define('DB_USER', 'root');
define('DB_PASSWORD', 'passw0rd');