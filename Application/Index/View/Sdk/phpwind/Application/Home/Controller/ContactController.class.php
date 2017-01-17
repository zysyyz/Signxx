<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class ContactController extends BaseController {

    public function index() {
        $this->assign('contact_selected', 'selected');
        $this->init();
        $this->display();
    }

    private function init() {
        if (!I('content')) {
            return;
        }

        $userID = $this->getUserId();
        $submitTime = date('Y-m-d H:i:s', time());
        $submitIP = HJCTool::getRealIP();
        $content = I('content');

        $mysql = M();
        $mysql->execute("INSERT INTO cloud_advise SET user_id = '$userID', submit_time = '$submitTime', submit_ip = '$submitIP', content = '$content'");
        HJCTool::alertToLocation('感谢您宝贵的建议!', '/Home/contact');
    }
}