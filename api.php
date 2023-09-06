<?php
require_once 'modules/mysql.php';
require 'config/main.php';
switch ($_GET['action']) {
    case 'update': //update所需要参数 action,username,password(SHA256加密后),ip
        $username = $_GET['username'];
        $ip = $_GET['ip'];
        $password = $_GET['password'];
        $res = check_ip($ip);
        if($res != 0) {
            echo $res;
            return;
        }

        if ($password == mysql_exec("SELECT password from user WHERE username = '$username'")['password']) {
            $message = dns_modify('modify', $username, $ip);
            $time = date("Y-m-d H:i:s");
            mysql_exec("UPDATE `user` SET `connect_time` = '$time' WHERE `user`.`username` = 'tanhao';");
            log_to_mysql($username, $message['message'], $message['detail'], 'api_update_record');
            echo $message['message'];
        }
        else echo '[Error]用户名或密码错误';
        break;

    case 'getip' :
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            echo explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            echo $_SERVER['REMOTE_ADDR'];
        }
        break;

    default :
        echo '[Error]action不存在';

}


