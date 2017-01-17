<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>Sign++免费网络验证</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="keywords" content="sign++ 云网络验证系统 软件验证 脚本验证">
    <meta name="description" content="免费的网络验证系统 提卡免费 可以管理多个软件 功能丰富">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/Public/css/index.css">
    <style type="text/css">
        div.content {
            padding: 30px 50px 30px 50px;
        }
        p {
            padding: 5px;
            letter-spacing: 2px;
            line-height: 20px;
        }
        h3 {
            padding-top: 10px;
        }
        dl {
            padding-left: 40px;
        }
        dl li {
            letter-spacing: 2px;
            padding: 3px;
        }
        h1 {
            display: none;
        }
    </style>
</head>
<div style="display: none">
    <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260520261'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1260520261' type='text/javascript'%3E%3C/script%3E"));</script>
</div>

<body>
<div class="container">
    <h1>Sign++免费网络验证</h1>
    <div class="navbar">
        <div class="logo"><img src="/Public/image/signxx.png"></div>
        <ul>
            <li><a href="/">首页</a></li>
            <li><a href="/intro" class="select">功能</a></li>
            <li><a href="/sdk">下载</a></li>
            <li><a href="/Home">后台</a></li>
        </ul>
    </div>
    <div class="content">
        <h3>Sign++简介</h3>
        <p>Sign在软件开发中代表着数据签名的意思，目的是为了保护数据安全，从而起到保护知识产权的作用。Sign++系统使用PHP配合MYSQL开发完成，数据传输使用RSA等加密方式加密保证数据无法被截取，系统主要有软件管理、注册码生成、注册码管理等功能，为软件作者的软件收费管理提供一个绝佳的选择方案。</p>
        <h3>后台系统介绍</h3>
        <p>* 以下功能不包括最新功能</p>
        <p>商户概况：记录最近的登录时间和IP起到安全提示作用；对所有软件的注册码使用情况进行统计展示。</p>
        <p>软件管理：可以新建和修改软件。</p>
        <dl>
            <li>试用时间或试用次数设置0代表软件不支持试用；</li>
            <li>绑机单开代表注册码只支持一台电脑使用并且每次只能打开一个软件；</li>
            <li> 绑机多开代表注册码支持一台电脑但是不限制软件打开数量；</li>
            <li>不绑机多开代表注册码使用没有任何限制；</li>
            <li>允许解绑代表注册码使用者可以在原绑定电脑解绑；</li>
            <li>不允许解绑代表只有作者可以解绑；</li>
            <li>软件版本和更新地址用于软件强制更新或提示更新；</li>
            <li>禁用功能会导致注册码无法登录使用；</li>
            <li>生成注册码可以生成任意小时或天数的注册码。</li>
            <li>注册码管理可以使用不同筛选条件查找注册码，进行注册码查看、冻结、删除等操作。</li>
            <li>批量操作可以批量对注册码进行解绑、冻结、删除操作。</li>
            <li>推荐人规则对推荐人和被推荐人进行奖励设置。</li>
        </dl>
        <h3>关于防破解</h3>
        <p>Sign++插件本身是具有防破解能力的。但是软件的防破解和插件是分开独立的，您的软件集成我们的插件后请一定要进行暗桩加壳等尽可能多的方式增加破解难度。</p>
    </div>
    <div class="bottom">
    Copyright © 2016 浙ICP备16035010号-1 Sign++免费网络验证
</div>

</div>
</body>
</html>