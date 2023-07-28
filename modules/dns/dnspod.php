<?php
function dns_modify($action, $username, $value) {
    require 'modules/http.php';
    $config = require 'config/dnspod.php';
    if(!empty($config['prefix'])) $prefix = $username . '.' . $config['prefix'];
    else $prefix = $username;
    $param["Nonce"] = rand();
    $param["Timestamp"] = time();
    $param["Region"] = "";
    $param["SecretId"] = $config['SecretId'];
    $param["Version"] = '2021-03-23';
    $param["Domain"] = $config['domain'];
    $param["Language"] ='zh-CN';

    switch ($action) {
        case 'add' :
            $param["Action"] = 'CreateRecord';
            $param["RecordType"] = 'A';
            $param["RecordLine"] = "默认";
            $param["Value"] = $value;
            $param["SubDomain"] = $prefix;
            break;
        case 'modify' :
            require_once 'modules/mysql.php';
            $param["Action"] = 'ModifyRecord';
            $param['RecordId'] = mysql_exec("SELECT recordid FROM user WHERE username = '$username'")['recordid'];
            $param["RecordType"] = 'A';
            $param["RecordLine"] = "默认";
            $param["Value"] = $value;
            $param["SubDomain"] = $prefix;
            break;

        case 'del' :
            require_once 'modules/mysql.php';
            $param['RecordId'] = mysql_exec("SELECT recordid FROM user WHERE username = '$username'")['recordid'];
            $param["Action"] = 'DeleteRecord';
            break;
    }

    ksort($param);
    $signStr = 'GETdnspod.tencentcloudapi.com/?';
    foreach ( $param as $key => $value ) {
        $signStr = $signStr . $key . "=" . $value . "&";
    }
    $signStr = substr($signStr, 0, -1);
    $signature = base64_encode(hash_hmac('sha1', $signStr, $config['SecretKey'], 'ture'));
    $signature = urlencode($signature);
    $url = str_replace('GET', 'https://' ,$signStr) . '&Signature=' . $signature;
    $re['detail'] = http('GET', $url, '');
    if ( strpos($re['detail'] , 'Error') > 0) {
        $re['message'] = '[Error]事件发生在DNS操作中，API响应错误';
    }
    elseif (empty($re['detail'])) {
        $re['message'] = '[Error]事件发生在DNS操作中，HTTP函数返回为空';
    }
    else {
        $re['message'] = '[Success]'.$action;
    }
    return $re;
}

function check_ddns_name($username) {
    $config = require 'config/dnspod.php';
    return $username . '.' . $config['prefix']  . '.' . $config['domain'];
}