<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/1/4 11:04
 */

require_once 'config.php';
require_once 'city.php';
require_once 'WeixinCallbackApi.php';
//require_once 'config.php';

if (defined("DEBUG_MODE") && !DEBUG_MODE){
    error_reporting(0);
}

$wechatObj = new weixinCallbackApi();
if (defined("VALIDATE_MODE") && VALIDATE_MODE){
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}






