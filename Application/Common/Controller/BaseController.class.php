<?php
// 所有控制器的父控制器

namespace Common\Controller;
use Home\Tool\HJCTool;
use Think\Controller;

class BaseController extends Controller {
    private $_currentPage = 1;
    private $_itemCountAPage = 10;
    private $_pageCount;
    private $_userId = -1;

    // 生成验证码图片
    public function getVerifyImg() {
        $config = array(
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'codeSet' => 'ABCDEFGHJKMNPRSTUVWXYZ',
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    public function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    // 系统的empty如果是0也会当做空
    protected function isEmpty($str){
        return $str == '';
    }

    public function __construct() {
        parent::__construct();

        // 接口url不用判断登录
        if (strstr($_SERVER[REQUEST_URI], '/Home/Auth')) {
            return;
        }

        // 判断登录状态
        if (!$_SESSION['user']) {
            if (strstr($_SERVER[REQUEST_URI], '/Home/login') || strstr($_SERVER[REQUEST_URI], '/Home/reg') || strstr($_SERVER[REQUEST_URI], '/Home/forget')) {
            } else {
                $this->signout();
            }
        } else {
            // 超过多少分钟未操作,清除登录状态,跳登录页
            if ($_SESSION['last_logn_time']) {
                $minute = (time() - $_SESSION['last_logn_time']) / 60;
                if ($minute > 60) {
                    $this->signout();
                }
            }
            $_SESSION['last_logn_time'] = time();
        }

        // 显示用户名
        $this->assign('username', $_SESSION['user']);
    }

    // 退出登录
    protected function signout(){
        unset($_SESSION['user']);
        HJCTool::alertToLocation(null, '/Home/login');
    }

    // 获取用户id,保存到属性变量
    protected function getUserId(){
        if ($this->_userId != -1) return $this->_userId;

        $mysql_user = M('User');
        $user_ret = $mysql_user->query("SELECT * FROM cloud_user WHERE username = '" . $_SESSION['user'] . "'");
        if (!$user_ret) {
            $this->signout();
        }
        $this->_userId = $user_ret[0]['id'];
        return $this->_userId;
    }

    // 处理table和分页
    // $table_name 表名
    // $sql sql语句,传不传处理不一样
    protected function showTableAndPage($table_name, $sql) {
        // 先查询总数,计算出页数
        $mysql = M(ucfirst($table_name)); // 首字母大写

        // 拿到用户id,只查询该用户下的数据库
        if ($sql) {
            $ret = $mysql->query($sql);
        } else {
            $userId = $this->getUserId();
            $limitUserSql = " WHERE user_id = '$userId'";
            $ret = $mysql->query("SELECT * FROM cloud_" . $table_name . $limitUserSql);
        }

        $this->_pageCount = ceil(sizeof($ret) / $this->_itemCountAPage);

        // 正整数正则,判断传进来的页数是不是超过限制
        if ($this->isEmpty(I('get.page', '', '/^[1-9]\d*$/'))) {
            $this->_currentPage = 1;
        } else {
            // 超过最大页数
            if (I('get.page') > $this->_pageCount) {
                $this->_currentPage = $this->_pageCount;
            } else {
                $this->_currentPage = $_GET['page'];
            }
        }

        $onePageSql;
        if ($sql) {
            $onePageSql = $sql . " LIMIT " . (($this->_currentPage - 1) * $this->_itemCountAPage) . ", " . $this->_itemCountAPage;
        } else {
            $onePageSql = "SELECT * FROM cloud_" . $table_name . $limitUserSql . " LIMIT " . (($this->_currentPage - 1) * $this->_itemCountAPage) . ", " . $this->_itemCountAPage;
        }

        // table内容
        $list = $mysql->query($onePageSql);
        $this->assign('list', $list);

        // 第一页
        if ($this->_currentPage > 4) {
            $this->assign('firstbtn', "<li><a href='?page=1'>1</a></li><li class='disabled'><a>...</a></li>");
        }

        // 数字页
        $_pagelist = '';
        for ($i = 3; $i >= 1; $i--) {
            $_page = $this->_currentPage - $i;
            if ($_page < 1) continue;
            $_pagelist .= '<li><a href="?page=' . $_page . '">' . $_page . '</a></li>';
        }
        $_pagelist .= '<li class="active"><a href="#">' . $this->_currentPage . '</a></li>';
        for ($i = 1; $i <= 3; $i++) {
            $_page = $this->_currentPage + $i;
            if ($_page > $this->_pageCount) continue;
            $_pagelist .= '<li><a href="?page=' . $_page . '">' . $_page . '</a></li>';
        }
        $this->assign('pagelist', $_pagelist);

        // 最后一页
        if ($this->_pageCount - $this->_currentPage > 3) {
            $this->assign('lastbtn', "<li class='disabled'><a>...</a></li><li><a href='?page=" . $this->_pageCount . "'>" . ($this->_pageCount) . "</a></li>");
        }

        // 上一页
        if ($this->_currentPage <= 1) {
            $this->assign('prevbtn', "<li class='disabled'><a href='#'>上一页</a></li>");
        } else {
            $this->assign('prevbtn', "<li><a href='?page=" . ($this->_currentPage - 1) . "'><span>上一页</span></a></li>");
        }

        // 下一页
        if ($this->_currentPage >= $this->_pageCount) {
            $this->assign('nextbtn', "<li class='disabled'><a href='#'>下一页</a></li>");
        } else {
            $this->assign('nextbtn', "<li><a href='?page=" . ($this->_currentPage + 1) . "'><span>下一页</span></a></li>");
        }
    }

    // 获取软件列表
    protected function getSoftwareList() {
        $userId = $this->getUserId();

        $mysql_soft = M('Software');
        $soft_ret = $mysql_soft->query("SELECT * FROM cloud_software WHERE user_id = '" . $userId . "'");
        if (!$soft_ret) {
            return '';
        }
        return $soft_ret;
    }
}