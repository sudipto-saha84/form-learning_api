<?php
require_once 'configurations/config.php';
require_once 'configurations/service_config.php';
require_once 'lib/functions.php';

date_default_timezone_set('Asia/Dhaka');

$fileName="Validate Email_".date('Y-m-d',time());
$messengerId=isset($_REQUEST['messengerId'])?$_REQUEST['messengerId']:"";
$email=isset($_REQUEST['email'])?$_REQUEST['email']:"";

$logTxt="REQUEST_".json_encode($_REQUEST).$logSeparator."MessengerId".$messengerId.$logSeparator."Email:".$email;

if (!filter_var($email,FILTER_VALIDATE_EMAIL))
{
    $logTxt.="Invalid Email".$logSeparator;
    $response['status']="UnSuccesful";
    $response['message']="InValid Email";
    $response['code']="200";
}
else
{

    $logTxt.="Valid Email".$logSeparator;
    $response['status']="Succesful";
    $response['message']="Valid Email";
    $response['code']="200";
}
logWrite($fileName,$logTxt);

sendJSONResponse($response);
