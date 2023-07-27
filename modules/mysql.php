<?php
function mysql_exec($sql_exec)
{
    $mysql = require 'config/database.php';
    $conn = mysqli_connect($mysql['host'], $mysql['username'], $mysql['password'], $mysql['database'], $mysql['port']);
    $res = mysqli_query($conn, $sql_exec);
    if (!empty($res)) $res = mysqli_fetch_array($res);
    mysqli_close($conn);
    return $res;
}

function log_to_mysql($username, $message, $detail, $action) {
    mysql_exec("INSERT INTO log(username,message,detail,action) VALUES('$username','$message','$detail','$action')");
}