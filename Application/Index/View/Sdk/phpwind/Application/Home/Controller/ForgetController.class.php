<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use hdphp\page\Page;
use Home\Tool\HJCTool;

require getcwd() . '/Application/Home/Common/Email/email.class.php';

class ForgetController extends BaseController {
    public function index() {
        $this->init();
        $this->display();
    }

    private function init() {
        if (!isset($_POST['forget'])) {
            return;
        }
        if (I('post.valid') == '') {
            // 这里不用alertBack因为验证码需要刷新页面
            HJCTool::alertToLocation('请输入验证码', '/Home/forget');
            return;
        }
        if (!$this->check_verify(I('post.valid'))) {
            HJCTool::alertToLocation('验证码错误', '/Home/forget');
        }
        if ($this->isEmpty(I('post.username', '', '/^[a-z0-9A-Z]{6,16}$/'))) {
            HJCTool::alertToLocation('账号格式错误: 请输入6-16位英文字母或数字', '/Home/forget');
        }
        if (I('post.email', '', FILTER_VALIDATE_EMAIL) == '') {
            HJCTool::alertToLocation('邮箱格式错误', '/Home/forget');
        }
        // 先看邮箱和用户名是不是对应
        $username = I('post.username');
        $email = I('post.email');
        $mysql = M();
        $ret = $mysql->query("SELECT * FROM cloud_user WHERE username = '$username' AND email = '$email'");
        if (!$ret) {
            HJCTool::alertToLocation('用户名或邮箱不正确', '/Home/forget');
        }
        // 如果有时间就判断是否小于xx时间 小于就返回
        $time = date('Y-m-d H:i:s', time());
        if ($ret[0]['forget_time']) {
            $second = strtotime($time) - strtotime($ret[0]['forget_time']);
            if ($second < 30 * 60) {
                HJCTool::alertToLocation('30分钟内请勿重新提交', '/Home/forget');
            }
        }

        // 生成随机密码 修改数据库 并发送邮件
        $newPwd = $this->rand();
        $secretPwd = HJCTool::secret($newPwd);
        $updateret = $mysql->execute("UPDATE cloud_user SET forget_time = '$time', password = '$secretPwd' WHERE username = '$username'");
        if ($updateret) {
            $this->sendEmail($username, $newPwd, $email);
        } else {
            HJCTool::alertToLocation('未知错误', '/Home/forget');
        }

    }

    //获取随机数
    private function rand( $len = 6 ) {
        $data = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str  = '';
        while ( strlen( $str ) < $len ) {
            $str .= substr( $data, mt_rand( 0, strlen( $data ) - 1 ), 1 );
        }
        return $str;
    }

    private function sendEmail($username, $password, $smtpemailto) {
        /**
         * 注：本邮件类都是经过我测试成功了的，如果大家发送邮件的时候遇到了失败的问题，请从以下几点排查：
         * 1. 用户名和密码是否正确；
         * 2. 检查邮箱设置是否启用了smtp服务；
         * 3. 是否是php环境的问题导致；
         * 4. 将26行的$smtp->debug = false改为true，可以显示错误信息，然后可以复制报错信息到网上搜一下错误的原因；
         * 5. 如果还是不能解决，可以访问：http://www.daixiaorui.com/read/16.html#viewpl
         *    下面的评论中，可能有你要找的答案。
         */

        //******************** 配置信息 ********************************
        $smtpserver = "smtp.126.com";//SMTP服务器
        $smtpserverport = 25;//SMTP服务器端口
        $smtpusermail = "signxx@126.com";//SMTP服务器的用户邮箱
        $smtpuser = "signxx";//SMTP服务器的用户帐号
        $smtppass = "aaa123";//SMTP服务器的用户密码
        $mailtitle = "Sign++密码找回";//邮件主题
        $mailcontent = $username . ",您好！您的新密码为: " . $password . "。请尽快登录并修改密码！";//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new \smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
        if($state == ''){
            HJCTool::alertToLocation('未知错误', '/Home/forget');
        } else {
            HJCTool::alertToLocation('邮件已发送,请及时查收邮件', '/Home/login');
        }
    }
}