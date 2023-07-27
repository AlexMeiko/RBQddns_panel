<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>仪表盘</title>
</head>
<body>
<div>
    <h1>登录成功</h1> 欢迎您
    <?php echo $username; ?><br>
    您的DDNS域名为 <?php echo check_ddns_name($username); ?>
</div>

</body>
</html>