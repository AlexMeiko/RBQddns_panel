<?php
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
echo <<<EOF
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>退出成功</title>
    <meta name="content-type"; charset="UTF-8">
</head>
<h1>退出成功！</h1><br>
<a href="Login">重新登入</a>
EOF;
return 0;

