<?php

/*
 * Function to unescaped UNICODE meshup Data
 */

function unescapedUnicodeJSON($utf8MeshupData) {
    $json = json_encode($utf8MeshupData, JSON_UNESCAPED_UNICODE);
    return $json;
}

//function for log writing
function logWrite($fileName, $logTxt) {
    global $logEnable, $logSeparator;

    if ($logEnable) {

        $file = fopen("logs/" . $fileName, 'a+');
        fwrite($file, date("Y-m-d H:i:s", time()) . $logSeparator . $logTxt . PHP_EOL);
        fclose($file);
    }
}

function logWriteDeeper($fileName, $logTxt) {
    global $logEnable, $logSeparator;

    if ($logEnable) {

        $file = fopen($fileName, 'a+');
        fwrite($file, date("Y-m-d H:i:s", time()) . $logSeparator . $logTxt . PHP_EOL);
        fclose($file);
    }
}

function SetDBInfo($dbservers, $dbnames, $dbusername, $dbpassword, $dbtypes) {
    global $Server;
    global $Database;
    global $UserID;
    global $Password;
    global $dbtype;

    $dbtype   = $dbtypes;
    $Server   = $dbservers;
    $Database = $dbnames;
    $UserID   = $dbusername;
    $Password = $dbpassword;
}

function getResponse($dataArray, $baseUrl) {//get dynamic url for each purpose using the token
    global $logTxt, $logSeparator;

    $ch      = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataArray));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Content-Type: application/json",
        "Accept: application/json",
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $serverOutput = curl_exec($ch);
    //$responseDecoded = json_decode($serverOutput, true);

    if (curl_errno($ch)) {
        $logTxt .= "cURL Error #:" . curl_error($ch);
    } else {
        $logTxt .= json_encode($dataArray) . $logSeparator . $baseUrl . $logSeparator . $serverOutput;
    }

    curl_close($ch);
    return $serverOutput;
}
/*
 * New important function added for bot API
 * added by : aditya 
 */


// function for check the session variable
function is_set_session($field) {
    if (isset($_SESSION[$field]) && !empty($_SESSION[$field])) {
        return true;
    } else {
        return false;
    }
}
//check the cookie variable
function is_set_cookie($field) {
    if (isset($_COOKIE[$field]) && !empty($_COOKIE[$field])) {
        return true;
    } else {
        return false;
    }
}

// send CURL post Request
function send_curl_post($url, $params = []) {
    $params_query_string = http_build_query($params);
    //CURL init
    $curl                = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params_query_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // get curl response
    $response = curl_exec($curl);
    // get curl error
    $err      = curl_error($curl);
    // curl close
    curl_close($curl);

    if ($err) {
        $message     = "cURL Error #:" . $err;
        $returnValue = [
            'status'  => 'error',
            'code'    => 400,
            'message' => $message
        ];
        return json_encode($returnValue);
    } else {
        return $response;
    }
}
// send CURL get Request
function send_curl_get($url, $params = []) {
    $params_query_string = http_build_query($params);
    $url_with_params     = $url . '?' . $params_query_string;
    $curl                = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url_with_params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "GET",
    ));

    // get curl response
    $response = curl_exec($curl);

    // get curl error
    $err = curl_error($curl);

    // curl close
    curl_close($curl);

    if ($err) {
        $message     = "cURL Error #:" . $err;
        $returnValue = [
            'status'  => 'error',
            'code'    => 400,
            'message' => $message
        ];
        return json_encode($returnValue);
    } else {
        return $response;
    }
}

// get the facebook post data
function get_facebook_post_data() {
    $post_json = file_get_contents("php://input");
    return json_decode($post_json, true);
}

//send JSON Respose
function sendJSONResponse($data) {
    $responseCode = isset($data['code'])?(int)$data['code']:400;
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json;charset=utf-8');
    http_response_code($responseCode);
    echo json_encode($data);
    exit;
}
