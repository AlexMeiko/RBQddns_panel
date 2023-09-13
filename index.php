<?php
header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');
header("Expires:0");
session_start();
$username = isset($_SESSION['user']) ? $_SESSION['user'] : "";
$err = isset($_GET["err"]) ? $_GET["err"] : "";
switch ($_SERVER['PATH_INFO']) {
    case '' :
        include 'pages/index.html';

/**        if (!empty($username)){
            header('Location:Dashboard');
        }
        else {
            header('Location:Login');
        }
        break;
**/

    case '/Login' :
        if (!empty($username)) header('Location:');
        include 'pages/login.html';
        switch ($err) {
            case 1 :
                echo "<script>alert('用户名或密码错误！请检查您的用户名或密码')</script>";
                break;

            case 2 :
                echo "<script>alert('用户名或密码为空！')</script>";
                break;
        }
        break;

    case '/Register' :
        include 'pages/register.html';
        switch ($err) {
            case 1 :
                echo "用户名已存在！";
                break;

            case 2 :
                echo "密码与重复密码不一致！";
                break;

            case 3 :
                echo '因未知原因，暂时无法注册，请联系网站管理员，并告知注册的大致时间及用户名';
                break;

            case 'n' :
                echo "注册成功！";
                break;
        }
        break;

    case '/LoginAction' :
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
        $password = hash("sha256", $password);
        $remember = isset($_POST['remember']) ? $_POST['remember'] : "";
        if (!empty($username) && !empty($password)) {
            require 'modules/mysql.php';
            if ($password == mysql_exec("SELECT password FROM user WHERE username = '$username'")['password']) {
                if ($remember == "on")
                {
                    setcookie("", $username, time() + 7 * 24 * 3600);
                }
                $_SESSION['user'] = $username;
                header('Location:Dashboard');
            }
            else header('Location:Login?err=1');
        }
        else header('Location:Login?err=2');
        break;

    case '/RegisterAction' :
        include 'modules/mysql.php';
        require 'config/main.php';
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $password = hash('sha256',isset($_POST['password']) ? $_POST['password'] : "");
        $re_password = isset($_POST['re_password']) ? $_POST['re_password'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        if ($_POST['password'] == $_POST['re_password']) {
            $a = mysql_exec("SELECT username FROM user WHERE username = '$username'");
           if ($username == $a['username'] ) header("Location:Register?err=1");
           else {
               $message = dns_modify('add', $username, '8.8.8.8');
               if (strpos($message['message'] , 'Error') > 0) {
                   $timestamp = time();
                   log_to_mysql($username, $message['message'], $message['detail'], 'register');
                   header('Location:Register?err=3');
               }
               else {
                   $recordid = json_decode($message['detail'])->Response->RecordId;
                   mysql_exec("INSERT INTO user(username,password,email,recordid) VALUES('$username','$password','$email','$recordid')");
                   log_to_mysql($username, $message['message'], $message['detail'], 'register');
                   header('Location:Register?err=n');
               }
           }
        }
        else header('Location:Register?err=2');
        break;

    case '/Dashboard' :
        if (!empty($username)) {
            require 'config/main.php';
            require 'modules/mysql.php';
            require 'modules/http.php';
            $sql = mysql_exec("SELECT status,connect_time FROM user WHERE username = '$username'");
            $userstatus = $sql['status'];
            $connect_time = $sql['connect_time'];
            $ddns_domain = check_ddns_name($username);
            $ip = http('GET', 'https://httpdns.api.mrsheep.cn/?type=a&domain=' . $ddns_domain, '');
            require 'pages/dashboard.php';

        }
        else {
            echo '<h1>您还没有登录哦！请先<a href="Login">登录</a>后再"食"用</h1>';
        }
        break;

    case '/API' :
        include 'api.php';
        break;

    default :
        include 'pages/404.html';
}
