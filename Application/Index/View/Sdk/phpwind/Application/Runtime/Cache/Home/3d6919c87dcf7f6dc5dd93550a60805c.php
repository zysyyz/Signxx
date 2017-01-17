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
        <input type="text" hidden="hidden" name="sid" value="<?php echo ($sid); ?>">
        <label>软件名称</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="software" value="<?php echo ($software); ?>" placeholder="请输入软件名">
        </div>
        <label>试用分钟</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="try_minutes" value="<?php echo ($try_minutes); ?>" placeholder="填0代表不可试用">
        </div>
        <label>试用次数</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="try_count" value="<?php echo ($try_count); ?>" placeholder="填0代表不可试用">
        </div>
        <div class="form-group">
            <label class="control-label ng-scope">绑机多开模式</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="bindmode" <?php echo ($bindmode0); ?>>
                        <span class="control-indicator"></span>
                        绑机单开
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="bindmode" <?php echo ($bindmode1); ?>>
                        <span class="control-indicator"></span>
                        绑机多开
                    </label>
                </div>

                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="2" class="ng-pristine ng-valid" name="bindmode" <?php echo ($bindmode2); ?>>
                        <span class="control-indicator"></span>
                        不绑机多开
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label ng-scope">用户解绑模式</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="unbindmode" <?php echo ($unbindmode0); ?>>
                        <span class="control-indicator"></span>
                        允许解绑
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="unbindmode" <?php echo ($unbindmode1); ?>>
                        <span class="control-indicator"></span>
                        不允许解绑
                    </label>
                </div>
            </div>
        </div>
        <label>软件版本</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="version" value="<?php echo ($version); ?>" placeholder="请输入非负整数">
        </div>
        <label>更新地址</label>
        <div class="form-group input-group-half">
            <input type="text" class="form-control" name="update_url" value="<?php echo ($update_url); ?>" placeholder="请输入url地址">
        </div>
        <div class="form-group">
            <label class="control-label ng-scope">更新模式</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="updatemode" <?php echo ($updatemode0); ?>>
                        <span class="control-indicator"></span>
                        不强制更新
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="updatemode" <?php echo ($updatemode1); ?>>
                        <span class="control-indicator"></span>
                        强制更新
                    </label>
                </div>
            </div>
        </div>
        <label>软件公告</label>
        <div class="form-group input-group-half ng-scope">
        <textarea class="form-control ng-pristine ng-valid" rows="4" name="info"><?php echo ($info); ?></textarea>
        </div>
        <div class="form-group">
            <label class="control-label ng-scope">软件状态改变</label>
            <div class="custom-form-inline custom-form-horizontal-form">
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="0" class="ng-pristine ng-valid" name="frozen" <?php echo ($frozen0); ?>>
                        <span class="control-indicator"></span>
                        正常
                    </label>
                </div>
                <div class="radio custom-control">
                    <label>
                        <input type="radio" value="1" class="ng-pristine ng-valid" name="frozen" <?php echo ($frozen1); ?>>
                        <span class="control-indicator"></span>
                        禁用
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary"><?php echo ($buttonTitle); ?></button>
    </form>
</div>
</body>
</html>