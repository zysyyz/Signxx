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
    .right-container form.form {
        width: 850px;
    }

    .topdiv {
        width: 100%;
        height: 74px;
    }

    .zhucema {
        width: 288px;
        float: left;
        margin-right: 10px;
    }

    .timechoose {
        margin-bottom: 12px;
        float: left;
    }

    .timechoose label {
        display: block;
    }

    .timechoose input {
        margin-bottom: 5px;
        margin-right: 2px;
        height: 34px;
        padding: 6px 10px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #d0d0d0;
        border-radius: 4px;
        box-shadow: inset 0 1 p
    }

    .timechoose input.fromtime {
        margin-left: 5px;
    }

    .topdiv button {
        float: right;
        margin-top: 25px;
    }

    .middlediv {
        width: 100%;
        height: 74px;
    }

    .software {
        width: 240px;
        float: left;
        margin-right: 10px;
    }

    .alltime {
        float: left;
    }

    .alltime input.form-control {
        width: 80px;
    }

    .alltime span {
        width: 100px;
    }

    div.unuse, div.online, div.expire {
        float: left;
        margin-left: 20px;
    }

    div.unuse label, div.online label, div.expire label {
        display: block;
    }

    div.checkbox {
        margin-top: 6px;
        float: left;
    }

    .checkbox + .checkbox {
        margin-left: 8px;
        margin-top: 6px;
    }

    .custom-control {
        padding-left: 18px;
    }

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
    td.codemark {
        max-width: 100px;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    button.exportbtn {
        float: right;
        margin-right: 10px;
        margin-bottom: 10px;
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
    <form method="get" class="form">
        <div class="topdiv">
            <div class="zhucema">
                <label>注册码</label>
                <div class="form-group">
                    <input type="text" class="form-control" name="regcode" value="<?php echo I(regcode);?>">
                </div>
            </div>

            <div class="timechoose">
                <label>选择时间</label>
                <select name="timerangetype" id="">
                    <option value="1" <?php echo I(timerangetype)==1?"selected='selected'":"";?>>过期时间</option>
                    <option value="2" <?php echo I(timerangetype)==2?"selected='selected'":"";?>>生成时间</option>
                    <option value="3" <?php echo I(timerangetype)==3?"selected='selected'":"";?>>开始使用时间</option>
                    <option value="4" <?php echo I(timerangetype)==4?"selected='selected'":"";?>>最后登录时间</option>
                </select>
                <input type="date" name="timerangefrom" value="<?php echo I(timerangefrom);?>">至
                <input type="date" name="timerangeto" value="<?php echo I(timerangeto);?>">
            </div>

            <button type="submit" name="search" class="btn btn-primary">搜索</button>
        </div>

        <div class="middlediv">
            <div class="software">
                <label>软件名称</label>
                <div class="input-group">
                    <input type="text" readonly="readonly" class="form-control softname-input" name="softname" value="<?php echo I(softname);?>">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">选择软件 <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php if(is_array($softlist)): $i = 0; $__LIST__ = $softlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a class="softname_btn" href="#"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            <li role="separator" class="divider"></li>
                            <li><a class="softname_btn_unchoose" href="#">不选择</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="alltime">
                <label>总共时长</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="time" value="<?php echo I(time);?>">
                    <span class="time input-group-addon">
        <input type="radio" name="time_type" value="hour" <?php echo I(time_type)=='hour'?"checked='checked'":"";?>> 小时&emsp;
                        <input type="radio" name="time_type" value="day" <?php echo I(time_type)=='day'?"checked='checked'":'';?>> 天
        </span>
                </div>
            </div>

            <div class="form-group online">
                <label class="control-label">是否在线</label>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="online_yes" <?php echo I(online_yes)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>在线
                    </label>
                </div>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="online_no" <?php echo I(online_no)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>不在线
                    </label>
                </div>
            </div>

            <div class="form-group expire">
                <label class="control-label">是否过期</label>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="expire_yes" <?php echo I(expire_yes)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>过期
                    </label>
                </div>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="expire_no" <?php echo I(expire_no)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>未过期
                    </label>
                </div>
            </div>

            <div class="form-group unuse">
                <label class="control-label">是否使用</label>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="inuse_yes" <?php echo I(inuse_yes)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>已用
                    </label>
                </div>
                <div class="checkbox custom-control">
                    <label>
                        <input type="checkbox" name="inuse_no" <?php echo I(inuse_no)=='on'?"checked='checked'":"";?>>
                        <span class="control-indicator"></span>未使用
                    </label>
                </div>
            </div>

        </div>

    </form>

    <hr>

    <form method="get">
        <button name="op" value="export1" class="btn btn-default exportbtn">导出注册码和推荐码</button>
        <button name="op" value="export2" class="btn btn-default exportbtn">导出注册码</button>
    </form>


    <table>
        <th>注册码</th>
        <th>软件</th>
        <th>时长</th>
        <th>生成时间</th>
        <th>到期时间</th>
        <th>使用次数</th>
        <th>在线</th>
        <th>备注</th>
        <th>推荐码</th>
        <th>操作</th>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["code"]); ?></td>
                <td><?php echo ($vo["software_name"]); ?></td>
                <td><?php echo ($vo["time_str"]); ?></td>
                <td><?php echo ($vo["produce_time"]); ?></td>
                <td><?php echo ($vo["expire_time"]); ?></td>
                <td><?php echo ($vo["use_count"]); ?></td>
                <td class="isonline"><?php echo ($vo["isonline"]); ?></td>
                <td class="codemark"><?php echo ($vo["mark"]); ?></td>
                <td class="recommendid"><?php echo ($vo["id"]); ?></td>
                <td class="opera">
                    <div>
                        <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='/Home/managecode/codedetail?code=<?php echo ($vo["code"]); ?>'">详情</button>

                        <button type="button" class="btn btn-danger btn-sm" onclick="del('<?php echo ($vo["code"]); ?>')">删除</button>
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
    function del(code) {
        if (confirm('确定删除吗?')) {
            if ('<?php echo ($querystr); ?>' != '') {
                window.location.href = '/Home/managecode?<?php echo ($querystr); ?>' + '&delcode=' + code;
            } else {
                window.location.href='/Home/managecode?delcode=' + code;
            }
        }
    }

    $('.softname_btn').click(function () {
        $('.softname-input').val($(this).text());
    });
    $('.softname_btn_unchoose').click(function () {
        $('.softname-input').val('');
    });

    window.onload = function () {
        var x = document.getElementsByClassName("isonline");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].innerText == '1') {
                x[i].innerText = '是';
            } else {
                x[i].innerText = '否';
            }
        }

        var x = document.getElementsByClassName("recommendid");
        var i;
        for (i = 0; i < x.length; i++) {
            x[i].innerText = parseInt(x[i].innerText) + 10000;
        }
    };
</script>
</html>