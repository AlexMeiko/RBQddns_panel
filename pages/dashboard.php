<!DOCTYPE html>
<html lang="zh">
<head>
    <title>个人中心</title>

    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .label {
            width: 120px;
            font-weight: bold;
        }

        .value {
            flex: 1;
        }
    </style>

</head>

<body>
<div class="container">

    <h2>用户面板</h2>

    <div class="user-info">
        <div class="label">用户名:</div>
        <div class="value" id="username"></div>
    </div>

    <div class="user-info">
        <div class="label">用户状态:</div>
        <div class="value" id="userstatus"></div>
    </div>

    <div class="user-info">
        <div class="label">DDNS域名:</div>
        <div class="value" id="ddns_domain"></div>
    </div>

    <div class="user-info">
        <div class="label">域名解析ip:</div>
        <div class="value" id="ip"></div>
    </div>

    <div class="user-info">
        <div class="label">解析更新时间:</div>
        <div class="value" id="connect_time"></div>
    </div>



</div>


<script>
    <?php
    echo "let username = '$username';";
    echo "let userstatus = '$userstatus';";
    echo "let ddns_domain = '$ddns_domain';";
    echo "let ip = '$ip';";
    echo "let connect_time = '$connect_time';";
    ?>

    // 输出到页面
    document.getElementById('username').innerText = username;
    document.getElementById('userstatus').innerText = userstatus;
    document.getElementById('ddns_domain').innerText = ddns_domain;
    document.getElementById('ip').innerText = ip;
    document.getElementById('connect_time').innerText = connect_time;

</script>
</body>
</html>