<?php
error_reporting(0);
require_once 'modules/mysql.php';
require 'config/main.php';
switch ($_GET['action']) {

    case 'update': //update所需要参数 action,username,password(SHA256加密后),ip
        require_once 'modules/dns/dnspod.php';
        require_once 'modules/check.php';
        $username = $_GET['username'];
        $ip = $_GET['ip'];
        $password = $_GET['password'];
        check_data($_GET['username']);
        check_data($_GET['password']);
        $res = check_ip($ip);
        if($res != 0) {
            echo $res;
            return;
        }

        if ($password == mysql_exec("SELECT password FROM user WHERE username = '$username'")['password']) {
            $message = dns_modify('modify', $username, $ip);
            $time = date("Y-m-d H:i:s");
            mysql_exec("UPDATE `user` SET `connect_time` = '$time' WHERE `user`.`username` = '$username';");
            log_to_mysql($username, $message['message'], $message['detail'], 'api_update_record');
            echo $message['message'];
        }
        else echo '[Error]用户名或密码错误';
        break;

    case "Login" :
        require_once 'modules/dns/dnspod.php';
        $username = $_GET['username'];
        $password = $_GET['password'];//sha256加密后
        check_data($_GET['username']);
        check_data($_GET['password']);
        if ($password == mysql_exec("SELECT password FROM user WHERE username = '$username'")['password']) {
            echo "[Success]" . check_ddns_name($username);
            log_to_mysql($username, '[Success]', ' ', 'API Login');
            return;
        } else {
            echo "[Error]用户名或密码错误";
            return;
        }
        break;


    case 'getip' :
        $time = date("Y-m-d H:i:s");
        $username = $_GET['username']; //option
        check_data($_GET['username']);
        if (!empty($username)) {
            mysql_exec("UPDATE `user` SET `connect_time` = '$time' WHERE `user`.`username` = '$username';");
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            echo explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            echo $_SERVER['REMOTE_ADDR'];
        }
        break;

    case 'online_update' :
        $username = $_GET['username'];
        $password = $_GET['password'];
        check_data($_GET['username']);
        check_data($_GET['password']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $message = dns_modify('modify', $username, $ip);
        if ($password != mysql_exec("SELECT password FROM user WHERE username = '$username'")['password']) {
            echo '[Error]用户名或密码错误';
            return;
        }
        log_to_mysql($username, $message['message'], $message['detail'], 'online_update');
        echo $message['message'];
        break;

 /**   case 'destroy' :
        require_once 'modules/dns/dnspod.php';
        $username = $_GET['username'];
        $password = $_GET['password'];
        check_data($_GET['username']);
        check_data($_GET['password']);
        if ($password == mysql_exec("SELECT password FROM user WHERE username = '$username'")) {
            $message = dns_modify('del', $username, '');
            mysql_exec("DELETE FROM `user` WHERE `user`.`username` = '$username'");
            log_to_mysql($username, $message['message'], $message['detail'], 'Destroy User');
        } else {
            echo '[Error]账号或密码不正确';
            return;
        }
**/
    default :
        echo '[Error]action不存在';

}
function check_data($str) {
    if (ctype_alnum($str) == false) exit("[Error]数据不合法");
}


