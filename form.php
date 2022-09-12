<?php

require_once 'configurations/config.php';
require_once 'configurations/service_config.php';
require_once 'lib/functions.php';

date_default_timezone_set('Asia/Dhaka');

$fileName="VALIDATE_".date('Y-m-d',time()).".txt";

$name=isset($_REQUEST['name'])?$_REQUEST['name']:"";
$email=isset($_REQUEST['email'])?$_REQUEST['email']:"";
$phone=isset($_REQUEST['phone'])?$_REQUEST['phone']:"";
$password=isset($_REQUEST['password'])?$_REQUEST['password']:"";

$logTxt="REQUEST_".json_encode($_REQUEST).$logSeparator."Name :".$name.$logSeparator."Email : $email".$logSeparator;
$logTxt.="Phone No :".$phone.$logSeparator."Password".$password.$logSeparator;

if (strlen($name)>4)
{
    if (strlen($phone)==11){

        if (strlen($password)>=6)
        {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $logTxt .= "Valid Email" . $logSeparator;


                $cn = connectDB();

                $qry="SELECT `email` FROM `new` WHERE `email` ='$email'";
                $result =Sql_exec($cn,$qry);
                $rowcount=mysqli_num_rows($result);

                $qry="SELECT number
                    FROM new
                        WHERE number ='$phone'";
                $result =Sql_exec($cn,$qry);
                $numberCount=mysqli_num_rows($result);


                if ($rowcount==0)
                {
                    if ($numberCount==0)
                    {
                        $qry = "insert into new (name,email,number,password) values ('$name','$email','$phone','$password')";
                        Sql_exec($cn, $qry);

                        $response['Status']="Successful";
                        $response['code']="200";
                        $response['message'] = "Record Stored Successfully";

                        ClosedDBConnection($cn);
                    }else
                    {
                        $logTxt .= "Duplicate number" . $logSeparator;
                        $response['Status']="Failed";
                        $response['code']="404";
                        $response['message'] = "Try with another Nummber : Number Already Exixts";
                    }


                }else{

                    $logTxt .= "Duplicate Email" . $logSeparator;
                    $response['Status']="Failed";
                    $response['code']="404";
                    $response['message'] = "Try with another Email : Email Already Exixts";
                }
                
            } else {
                $logTxt .= "InValid Email" . $logSeparator;
                $response['status']="Failed";
                $response['code']=401;
                $response['message'] = "Wrong Email !!";
            }

        }
        else
        {
            $logTxt .= "InValid Password" . $logSeparator;
            $response['status']="Failed";
            $response['code']=402;
            $response['message'] = "Password Atleast 6 Digit !!";

        }

    }else{
        $logTxt .= "InValid Phone" . $logSeparator;
        $response['status']="Failed";
        $response['code']=403;
        $response['message'] = "Phone Number Atleast 11 digit !!";

    }

}else
{
    $logTxt .= "InValid Name" . $logSeparator;
    $response['email'] = $email;
    $response['Phone'] = $phone;
    $response['password'] = md5($password);
    $response['message'] = "Name Must be Atleast 4 digit !!";
}



logWrite($fileName,$logTxt);
sendJSONResponse($response);



