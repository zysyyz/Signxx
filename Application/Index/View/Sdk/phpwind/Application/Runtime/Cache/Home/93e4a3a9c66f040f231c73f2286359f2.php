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
    .right-container button {
        margin-bottom: 10px;
        display: block;
    }

    .right-container table {
        width: 100%;
    }

    .right-container table th {
        height: 35px;
        background: #f7f7f7;
        color: #666;
        text-align: center;
    }

    .right-container table td {
        height: 30px;
        color: #999;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .right-container table td.opera div {
        width: 104px;
        height: 36px;
        margin: 0 auto;
    }

    .right-container table td button {
        float: left;
        margin-bottom: 0;
        margin-top: 3px;
    }

    .right-container table td button:nth-child(2) {
        margin-left: 12px;
    }

    .pagination:nth-child(2) {
        margin-left: 12px;
    }

    nav.page {
        width: 100%;
        height: 79px;
        text-align: center;
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
    <button type="submit" name="create" class="btn btn-primary" onclick="window.location.href='changesoft'">新建软件</button>
    <table>
        <th>名称</th>
        <th>编号</th>
        <th>版本</th>
        <th>绑定模式</th>
        <th>解绑模式</th>
        <th>更新模式</th>
        <th>软件状态</th>
        <th>操作</th>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["name"]); ?></td>
                <td><?php echo ($vo["id"]); ?></td>
                <td><?php echo ($vo["version"]); ?></td>
                <td class="bindmode"><?php echo ($vo["bindmode"]); ?></td>
                <td class="unbindmode"><?php echo ($vo["unbindmode"]); ?></td>
                <td class="updatemode"><?php echo ($vo["updatemode"]); ?></td>
                <td class="frozen"><?php echo ($vo["frozen"]); ?></td>
                <td class="opera">
                    <div>
                        <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='changesoft?sid=<?php echo ($vo["id"]); ?>'">修改</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteSoft('<?php echo ($vo["id"]); ?>')">删除</button>
                    </div>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <nav class="page">
        <div>
            <ul class="pagination">
                <?php echo ($firstbtn); ?>
                <?php echo ($pagelist); ?>
                <?php echo ($lastbtn); ?>
            </ul>
            <ul class="pagination">
                <?php echo ($prevbtn); ?>
                <?php echo ($nextbtn); ?>
            </ul>
        </div>
    </nav>
</div>
</body>
<script>
    function deleteSoft(id) {
        if(confirm("该软件下的所有注册码也将会被删除!\n确定删除该软件吗?")){
            if ('<?php echo ($querystr); ?>' != '') {
                window.location.href = 'managesoft?<?php echo ($querystr); ?>' + '&delsid=' + id;
            } else {
                window.location.href = 'managesoft?delsid=' + id;
            }
        }
    }
    window.onload = function () {
        bindmode();
        unbindmode();
        updatemode();
        frozen();
    };
    function bindmode(){
        var x = document.getElementsByClassName("bindmode");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].innerText == '0') {
                x[i].innerText = '绑机单开';
            } else if (x[i].innerText == '1') {
                x[i].innerText = '绑机多开';
            } else {
                x[i].innerText = '不绑机多开';
            }
        }
    }
    function unbindmode() {
        var x = document.getElementsByClassName("unbindmode");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].innerText == '0') {
                x[i].innerText = '允许解绑';
            } else if (x[i].innerText == '1') {
                x[i].innerText = '不允许解绑';
            }
        }
    }
    function updatemode() {
        var x = document.getElementsByClassName("updatemode");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].innerText == '0') {
                x[i].innerText = '强制更新';
            } else if (x[i].innerText == '1') {
                x[i].innerText = '提示更新';
            }
        }
    }
    function frozen() {
        var x = document.getElementsByClassName("frozen");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].innerText == '0') {
                x[i].innerText = '正常';
            } else if (x[i].innerText == '1') {
                x[i].innerText = '禁用';
            }
        }
    }
</script>
</html>