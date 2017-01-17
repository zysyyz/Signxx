<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use hdphp\page\Page;
use Home\Tool\HJCTool;

class RegController extends BaseController {
    public function index() {
        $this->reg();
        $this->display();
    }

    private function reg() {
        if (!isset($_POST['reg'])) {
            return;
        }
        if (I('post.valid') == '') {
            // 这里不用alertBack因为需要刷新页面
            HJCTool::alertToLocation('请输入验证码', '/Home/reg');
            return;
        }
        if (!$this->check_verify(I('post.valid'))) {
            HJCTool::alertToLocation('验证码错误', '/Home/reg');
        }
        if (I('post.username', '', '/^[a-z0-9A-Z]{6,16}$/') == '') {
            HJCTool::alertToLocation('账号格式错误: 请输入6-16位英文字母或数字', '/Home/reg');
        }
        if (I('post.password', '', '/^[a-z0-9A-Z]{6,16}$/') == '') {
            HJCTool::alertToLocation('密码格式错误: 请输入6-16位英文字母或数字', '/Home/reg');
        }
        if (I('post.email', '', FILTER_VALIDATE_EMAIL) == '') {
            HJCTool::alertToLocation('邮箱格式错误', '/Home/reg');
        }
        $username = I('post.username');
        $password = HJCTool::secret(I('post.password'));
        $email = I('post.email');

        $mysql = M('user');
        if ($mysql->query("SELECT * FROM cloud_user WHERE username='" . $username . "'")) {
            HJCTool::alertBack('此用户名已存在');
        }

        $ret = $mysql->execute("INSERT INTO cloud_user (username, password, email, reg_time) VALUES ('$username', '$password', '$email', NOW())");
        if ($ret) {
            HJCTool::alertToLocation('注册成功!', '/Home/login');
        }
    }

}