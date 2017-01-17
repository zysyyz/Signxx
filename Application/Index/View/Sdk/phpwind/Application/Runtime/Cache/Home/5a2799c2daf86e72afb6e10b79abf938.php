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
    <script src="/Public/js/common.js"></script>
</head>
<style>
div.form-group{
    margin-bottom: 8px;
}
    div.input-group{
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
        <label>注册码</label>
        <div class="input-group input-group-half">
            <input type="text" readonly="readonly" class="form-control softname-input" name="code" value="<?php echo I('code');?>">
            <div class="input-group-btn">
                <button type="submit" name="unbind" class="btn btn-primary dropdown-toggle">解绑电脑</button>
            </div>
        </div>
    </form>

    <form method="get">
        <input type="text" hidden name="code" value="<?php echo I('code');?>">
        <div class="form-group">
            <label class="control-label ng-scope">使用状态</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="frozen" <?php echo ($frozen==0?'checked':''); ?>>
                        <span class="control-indicator"></span>
                        正常
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="frozen" <?php echo ($frozen==1?'checked':''); ?>>
                        <span class="control-indicator"></span>
                        冻结
                    </label>
                </div>
            </div>
        </div>
        <label>备注</label>
        <div class="form-group input-group-half ng-scope">
            <textarea class="form-control ng-pristine ng-valid" rows="6" name="mark"><?php echo ($mark); ?></textarea>
        </div>
        <button type="submit" name="change" class="btn btn-primary">修改</button>
    </form>
    <hr>

    <label>所属软件</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($software_name); ?>">
    </div>
    <label>时长</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($time_str); ?>">
    </div>
    <label>生成时间</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($produce_time); ?>">
    </div>
    <label>开始时间</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($beginuse_time); ?>">
    </div>
    <label>到期时间</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($expire_time); ?>">
    </div>
    <label>最后登录时间</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($last_time); ?>">
    </div>
    <label>最后登录IP</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($last_ip); ?>">
    </div>
    <label>使用次数</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($use_count); ?>">
    </div>
    <label>在线状态</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($online); ?>">
    </div>
    <label>绑定状态</label>
    <div class="form-group input-group-half">
        <input type="text" class="form-control" readonly value="<?php echo ($bindtype); ?>">
    </div>
</div>
</body>
</html>