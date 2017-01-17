<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class SupportController extends BaseController {

    public function index() {
        $this->assign('support_selected', 'selected');
        $this->display();
    }
}