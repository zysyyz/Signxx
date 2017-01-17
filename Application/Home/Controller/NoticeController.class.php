<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class NoticeController extends BaseController {

    public function index(){
        $this->assign('home_selected', 'selected');
        $this->init();
        $this->display();
    }

    private function init() {
        if (I('nid') != '') {
            $id = I('nid');
            $mysql = M('Notice');
            $ret = $mysql->query("SELECT * FROM cloud_notice WHERE id = '$id'");
            if ($ret) {
                $this->assign('title', $ret[0]['title']);
                // 回车替换
                $content = str_replace('##', '<br>', $ret[0]['content']);
                $this->assign('content', $content);
            }

        }
    }

}