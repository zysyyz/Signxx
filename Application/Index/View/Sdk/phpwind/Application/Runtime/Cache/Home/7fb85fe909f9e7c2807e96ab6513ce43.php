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

    <form method="get">
        <label>软件名称</label>
        <div class="input-group input-group-half">
            <input type="text" readonly="readonly" class="form-control softname-input" name="softname" value="<?php echo I('softname');?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">选择软件 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php if(is_array($softlist)): $i = 0; $__LIST__ = $softlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a class="softname_btn" href="#"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <label>生成张数</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="count" value="<?php echo I('count')?I('count'):1;?>">
        </div>
        <label>总共时长</label>
        <div class="input-group input-group-half">
            <input type="text" class="form-control" name="time" value="<?php echo I('time')?I('time'):30;?>">
            <span class="time input-group-addon">
            <input type="radio" name="time_type" value="hour"
                   <?php echo I(time_type)=='hour'?"checked":"";?>> 小时&emsp;
            <input type="radio" name="time_type" value="day"
                   <?php echo I(time_type)=='hour'?"":"checked";?>> 天
        </span>
        </div>
        <label>备注</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="mark" value="<?php echo I('mark')?I('mark'):'';?>">
        </div>
        <div class="form-group">
            <label class="control-label ng-scope">生成结果类型</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="creat_type" checked="checked">
                        <span class="control-indicator"></span>
                        注册码形式
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="creat_type">
                        <span class="control-indicator"></span>
                        注册码加推荐码形式
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" name="produce" class="btn btn-primary">开始生成</button>
    </form>

    <br><br><br>
    <label>生成结果</label>
    <div class="form-group input-group-half ng-scope">
        <textarea class="form-control ng-pristine ng-valid" id="generalAppDesc" ng-model="app.description"
                  rows="10"><?php if(is_array($code_list)): $i = 0; $__LIST__ = $code_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($vo); ?>&#13;<?php endforeach; endif; else: echo "" ;endif; ?></textarea>
    </div>

</div>
</body>
<script>
    $(function () {
        // 默认显示第一个软件名字
        var firstSoftWareName = $('.softname_btn:nth(0)').text();
        $('.softname-input').val(firstSoftWareName);
    });

    $('.softname_btn').click(function () {
        $('.softname-input').val($(this).text());
    });
</script>
</html>