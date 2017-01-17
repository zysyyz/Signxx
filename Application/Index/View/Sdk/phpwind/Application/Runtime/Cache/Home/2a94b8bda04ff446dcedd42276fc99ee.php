<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>Sign++免费网络验证 后台管理</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="keywords" content="sign++ 云网络验证系统 软件验证 脚本验证">
    <meta name="description" content="免费的网络验证系统 提卡免费 可以管理多个软件 功能丰富">
    <link rel="stylesheet" href="/Public/css/a100e2e6.app.css">
    <link rel="stylesheet" href="/Public/css/common.css">
    <script src="/Public/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/js/bootstrap.min.js"></script>
    <script src="/Public/js/common.js"></script>
</head>
<style>
    div.input-group {
        width: 220px;
    }
    select {
        border:none;
        padding:0;
        border-radius: 0;
        cursor: pointer;
    }
    #code-numbers{
        height: 160px;
    }
    div.panel {
        border-radius: 0;
    }
    div.panel-body ul li a {
        color: #333;
        padding: 5px;
    }
    div.panel-body ul li a:hover {
        text-decoration: none;
        color: #666;
    }
    div.tongji {
        width: 25%;
        float: left;
    }
    ul.notice li {
        margin-bottom: 8px;
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

    <select id="select" onchange="change()">
        <option>全部软件</option>
        <?php if(is_array($softlist)): $i = 0; $__LIST__ = $softlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>

    <div id="code-numbers">
        <div class="tongji">
            <div class="stat-largetype-wrap">
                <h4 class="stat-largetype">
                    未使用
                    <span class="transition-number">
                        <?php echo ($unuse_count?$unuse_count:0); ?>
                    </span>
                </h4>
            </div>
        </div>
        <div class="tongji">
            <div class="stat-largetype-wrap">
                <h4 class="stat-largetype">
                    使用中
                    <span class="transition-number">
                        <?php echo ($use_count?$use_count:0); ?>
                    </span>
                </h4>
            </div>
        </div>
        <div class="tongji">
            <div class="stat-largetype-wrap">
                <h4 class="stat-largetype">
                    在线
                    <span class="transition-number">
                        <?php echo ($online_count?$online_count:0); ?>
                    </span>
                </h4>
            </div>
        </div>
        <div class="tongji">
            <div class="stat-largetype-wrap">
                <h4 class="stat-largetype">
                    冻结
                    <span class="transition-number">
                        <?php echo ($frozen_count?$frozen_count:0); ?>
                    </span>
                </h4>
            </div>
        </div>
    </div>

    <div class="callout callout-content ng-scope">
        本次登录IP：<span id="curr_ip"><?php echo ($currentlogin_ip); ?></span><br>
        上次登录IP：<span id="last_ip"><?php echo ($lastlogin_ip); ?></span><br>
        上次登录时间：<?php echo ($lastlogin_time); ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">公告</div>
        <div class="panel-body">
            <ul class="notice">
                <?php if(is_array($notices)): $i = 0; $__LIST__ = $notices;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/Home/notice?nid=<?php echo ($vo["id"]); ?>">[<?php echo ($vo["creat_time"]); ?>] <?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>

</div>

</body>
<script>
    window.onload = function() {
        var curr_ip = document.getElementById('curr_ip');
        var last_ip = document.getElementById('last_ip');
        if (curr_ip.innerText != last_ip.innerText) {
            last_ip.style.color = '#ff0011';
        }

        var select = document.getElementById('select');
        select.selectedIndex = '<?php echo I(softindex);?>';
    }

    function change(selectObject) {
        var select = document.getElementById('select');
        window.location.href = '?softindex=' + select.selectedIndex;
    }

</script>
</html>