<?php

require_once 'configurations/config.php';
require_once 'configurations/service_config.php';
require_once 'lib/functions.php';
date_default_timezone_set('Asia/Dhaka');


$fileName="VALIDATE EMAIL_".date('Y-m-d',time());

$messengerID=isset($_REQUEST['messengerId'])?$_REQUEST['messengerId']:"";
$email=isset($_REQUEST['email'])?$_REQUEST['email']:"";

$logTxt="REQUEST".json_encode($_REQUEST).$logSeparator."MessengerID :". $messengerID . $logSeparator."Email: ".$email.$logSeparator;

if (!filter_var($email,FILTER_VALIDATE_EMAIL))
{
    $logTxt.="Invalid Email".$logSeparator.
        $response['status']="Invalid";
    $response['message']="Invalid Email";
    $response['code']=200;
}else
{
    $logTxt.="Valid Email".$logSeparator.
        $response['status']="Valid";
        $response['message']="Valid Email";
        $response['code']=200;

}
logWrite($fileName,$logTxt);

sendJSONResponse($response);

