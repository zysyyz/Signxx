<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>Sign++免费网络验证 注册页</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="keywords" content="sign++ 云网络验证系统 软件验证 脚本验证">
    <meta name="description" content="免费的网络验证系统 提卡免费 可以管理多个软件 功能丰富">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/Public/css/a100e2e6.app.css">
    <link rel="stylesheet" href="/Public/css/common.css">
    <script src="/Public/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/common.js"></script>
</head>
<style>
    body{
        background: #f1f4f7;
    }
    img.logo {
        height: 28px;
        margin: 0 auto;
        display: block;
        margin-bottom: 32px;
    }
    .view {
        margin: 0 auto;
        padding: 100px;
    }
    .view form {
        width: 400px;
        margin: 0 auto;
        background: white;
        padding: 20px 50px 20px 50px;
    }
    img#valid{
        height: 34px;
        margin-bottom: 10px;
        float: right;
        margin-bottom: 20px;
    }
    input#inputValid {
        width: 180px;
        float: left;
        margin-bottom: 20px;
    }
    label.valid {
        display: block;
    }
</style>

<div style="display: none">
    <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260520261'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1260520261' type='text/javascript'%3E%3C/script%3E"));</script>
</div>

<body>
<div class="view">
    <form class="login-form" role="form" id="signupform" name="signupForm" method="post">

        <a href="/"  target="_blank"><img class="logo" src="/Public/image/signxx.png" alt="进入官网"></a>

        <div class="form-group">
            <label for="inputUsername" class="control-label">用户名</label>
            <input type="text" class="form-control" id="inputUsername" name="username" tabindex="1" placeholder="6-16位英文字母或数字">
        </div>

        <div class="form-group">
            <label for="inputPassword" class="control-label">密码</label>
            <input type="password" class="form-control" id="inputPassword" name="password"
                   tabindex="2" placeholder="6-16位英文字母或数字">
        </div>


        <div class="form-group">
            <label for="inputEmail" class="control-label">邮箱</label>
            <input type="text" class="form-control" id="inputEmail" name="email" tabindex="3" placeholder="用于找回密码">
        </div>


        <label for="inputUsername" class="control-label valid">验证码</label>
        <input type="text" class="form-control" id="inputValid" name="valid"
               tabindex="4" placeholder="6-16位英文字母或数字">
        <img src="/Home/login/getVerifyImg" id="valid" onclick="create_code()">

        <div class="form-group">
            <button type="submit" name="reg" class="btn btn-primary" tabindex="5"
                    >注册
            </button>
            &nbsp;
            已有账号?&nbsp;<a href="/Home/login">登录</a>
        </div>
    </form>
</div>
</body>
</html>