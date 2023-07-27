<?php
function http($Type,$Url,$Data) {
/**
    $Header = array(
        'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
        'Connection:Keep-Alive'
    );
 **/
    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);
    curl_setopt($ch, CURLOPT_URL, $Url);
    if ($Type == "POST") {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $res = curl_exec($ch);
//    print_r(curl_getinfo($ch));
    curl_close($ch);
    return $res;
}