<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>Sign++免费网络验证 后台管理</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="keywords" content="sign++ 云网络验证系统 软件验证 脚本验证">
    <meta name="description" content="免费的网络验证系统 提卡免费 可以管理多个软件 功能丰富">
    <link rel="stylesheet" href="/Public/css/a100e2e6.app.css">
    <link rel="stylesheet" href="/Public/css/common.css">
    <script src="/Public/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
</head>
<style>
    span.time {
        color: #666;
    }

    div.input-group-half {
        margin-bottom: 8px;
    }
    div.head {
        margin-bottom: 5px;
    }
    span.button {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
        color: #333;
        background-color: #f5f5f5;
        background-image: linear-gradient(to bottom,#fff 0,#f5f5f5 100%);
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        border-color: #d0d0d0;
        border-bottom-color: #c3c3c3;
        touch-action: manipulation;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,.08);
        margin-left: 10px;
    }
    span.button:hover {
        color: #3090e4;
        border-color: #3090e4;
        background-color: #fff;
    }
    ul.recommend {
        padding: 0 0 0 5px;
        margin-bottom: 3px;
        background: #f9f9f9;
    }
    ul.recommend input {
        width: 80px;
        text-align: center;
        margin: 0 2px 0 2px;
        height: 26px;
    }
    ul.recommend li {
        padding: 5px;
    }
    div.desc {
        margin-top: 40px;
    }
</style>
<body>
<nav class="lc-bd ng-scope">
    <nav class="dashboard-subnav navbar  navbar-static-top" role="navigation">
        <div class="container-fluid">
            <ul>
                <li class="logo"><a href="/"  target="_blank"><img src="/Public/image/logo.png" alt="进入官网"></a></li>
                <li class="dropdown" <?php echo ($username==''?'hidden':''); ?>>
                    <a role="button" class="dropdown-toggle user-name" data-toggle="dropdown">
                        <span class="user-name-text">
                            <?php echo ($username); ?>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="/Home/modifypassword">修改密码</a>
                        </li>
                        <li>
                            <a href="/Home/?a=logout">退出登录</a>
                        </li>
                    </ul>
                </li>
                <li class="browser_tip">（如页面显示有问题请使用谷歌或火狐浏览器）</li>
            </ul>
        </div>
    </nav>
</nav>

<div style="display: none">
    <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260520261'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1260520261' type='text/javascript'%3E%3C/script%3E"));</script>
</div>

<div class="left-container">
    <ul class="nav sidenav" style="margin-top:-15px">
        <li>
            <a class="disable" href="javascript:void(0)">商户信息 </a>
            <ul class="nav">
                <li>
                    <a class="<?php echo ($home_selected); ?>" href="/Home">商户概况 </a>
                </li>
            </ul>
        </li>
        <li>
            <a class="disable" href="javascript:void(0)">软件提卡 </a>
            <ul class="nav">
                <li>
                    <a class="<?php echo ($managesoft_selected); ?>" href="/Home/managesoft">软件管理 </a>
                </li>
                <li>
                    <a class="<?php echo ($createcode_selected); ?>" href="/Home/createcode">生成注册码 </a>
                </li>
                <li>
                    <a class="<?php echo ($managecode_selected); ?>" href="/Home/managecode">注册码管理</a>
                </li>
                <li>
                    <a class="<?php echo ($batchopera_selected); ?>" href="/Home/batchopera">批量操作</a>
                </li>
                <li>
                    <a class="<?php echo ($recommend_selected); ?>" href="/Home/recommend">推荐人规则</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="disable" href="javascript:void(0)">其他 </a>
            <ul class="nav">
                <li>
                    <a href="http://www.signxx.com/sdk">下载</a>
                </li>
                <li>
                    <a class="<?php echo ($contact_selected); ?>" href="/Home/contact">联系我们</a>
                </li>
            </ul>
        </li>
    </ul>
</div>

<div class="right-container">

    <form method="get" id="form">
        <div class="head">
            <label>推荐人赠送规则</label>&emsp;
            <span class="button" onclick="addRule(1)">新增规则</span>
        </div>
        <ul class="recommend" id="recommend1">
            <?php if(is_array($recommenderRules)): $i = 0; $__LIST__ = $recommenderRules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <span>如果注册码总时长等于</span><input type="text" class="originDay1" value="<?php echo ($vo["origin_day"]); ?>" onchange='inputChange()'><span>天，赠送时长</span><input type=text class='giveDay1' value="<?php echo ($vo["give_day"]); ?>" onchange='inputChange()'><span>天</span><span class=button onclick='del()'>删除规则</span>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <hr>
        <div class="head">
            <label>被推荐人赠送规则</label>
            <span class="button" onclick="addRule(2)">新增规则</span>
        </div>
        <ul class="recommend" id="recommend2">
            <?php if(is_array($recommendedRules)): $i = 0; $__LIST__ = $recommendedRules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <span>如果注册码总时长等于</span><input type="text" class="originDay2" value="<?php echo ($vo["origin_day"]); ?>" onchange='inputChange()'><span>天，赠送时长</span><input type=text class='giveDay2' value="<?php echo ($vo["give_day"]); ?>" onchange='inputChange()'><span>天</span><span class=button onclick='del()'>删除规则</span>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <input id="op" type="text" name="op" hidden="hidden">
    </form>

    <hr>
    <button onclick="confirm()" class="btn btn-primary">保存</button>
    <div class="desc">
        <div class="callout callout-info ng-scope">
            <span>建议注册码时长7天赠送1天，注册码时长30天赠送4天。</span><br>
            <span><strong>推荐人</strong>指提供推荐码的用户</span><br>
            <sapn><strong>被推荐人</strong>指使用推荐码的用户</sapn>
        </div>
    </div>
</div>
</body>
<script>
    // 增加规则
    function addRule(index) {
        if (index == 1) {
            var ul = document.querySelector('#recommend1');
            ul.innerHTML = ul.innerHTML + "<li><span>如果注册码总时长等于</span><input type=text class='originDay1' onchange='inputChange()'><span>天，赠送时长</span><input type=text class='giveDay1' onchange='inputChange()'><span>天</span><span class=button onclick='del()'>删除规则</span></li>";
        } else {
            var ul = document.querySelector('#recommend2');
            ul.innerHTML = ul.innerHTML + "<li><span>如果注册码总时长等于</span><input type=text class='originDay2' onchange='inputChange()'><span>天，赠送时长</span><input type=text class='giveDay2' onchange='inputChange()'><span>天</span><span class=button onclick='del()'>删除规则</span></li>";
        }
    }

    // 删除规则
    function del() {
        var ul = window.event.target.parentNode.parentNode;
        var li = window.event.target.parentNode;
        ul.removeChild(li);
    }

    // 保存input的值
    function inputChange() {
        var input = window.event.target;
        input.setAttribute('value', input.value);
    }

    // 确定
    function confirm() {
        setRecommenderName();
        setRecommendedName();

        document.getElementById('op').value = 'save';
        document.getElementById('form').submit();
    }

    // 给推荐人规则设置name数组
    function setRecommenderName() {
        var originDays = document.getElementsByClassName('originDay1')
        var giveDays = document.getElementsByClassName('giveDay1')
        for (var i=0; i<originDays.length; i++) {
            originDays[i].setAttribute('name', 'recommenderRule[' + i + '][originDay]');
            giveDays[i].setAttribute('name', 'recommenderRule[' + i + '][giveDay]');
        }
    }

    // 给被推荐人规则设置name数组
    function setRecommendedName() {
        var originDays = document.getElementsByClassName('originDay2')
        var giveDays = document.getElementsByClassName('giveDay2')
        for (var i=0; i<originDays.length; i++) {
            originDays[i].setAttribute('name', 'recommendedRule[' + i + '][originDay]');
            giveDays[i].setAttribute('name', 'recommendedRule[' + i + '][giveDay]');
        }
    }

</script>
</html>