<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class IndexController extends BaseController {
    private $_softwares;

    public function index(){
        $this->assign('home_selected', 'selected');
        $this->init();
        $this->display();
    }

    // 退出按钮会调用
    public function logout() {
        $this->signout();
    }

    private function init() {
        $mysql_user = M('User');
        $user_ret = $mysql_user->query("SELECT * FROM cloud_user WHERE username = '" . $_SESSION['user'] . "'");
        if (!$user_ret) {
            $this->signout();
        }

        // 本次登录IP 等信息
        $this->assign('currentlogin_ip', $user_ret[0]['currentlogin_ip']);
        $this->assign('lastlogin_time', $user_ret[0]['lastlogin_time']);
        $this->assign('lastlogin_ip', $user_ret[0]['lastlogin_ip']);

        $this->loadNotice();
        $this->querySoftware();
    }

    private function querySoftware() {
        $this->_softwares = $this->getSoftwareList();
        if ($this->_softwares == '') {
            return;
        }
        $this->assign('softlist', $this->_softwares);
        $this->queryCode();
    }

    // 公告
    private function loadNotice() {
        $mysql = M('Notice');
        $ret = $mysql->query("SELECT * FROM cloud_notice");
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'id',       //排序字段
        );
        $arrSort = array();
        foreach($ret AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $ret);
        }
        if ($ret) {
            $this->assign('notices', $ret);
        }
    }

    private function queryCode() {
        $userId = $this->getUserId();
        $mysql = M('Regcode');

        if (I('softindex', '', '/^\d+$/') != '' && I('softindex') != '0') {
            $softid = $this->_softwares[I('softindex') - 1]['id'];
            $sqlTail = " AND software_id = '$softid'";
        }

        $unuse_ret = $mysql->query("SELECT COUNT(*) FROM cloud_regcode WHERE user_id = '$userId' AND beginuse_time IS NULL " . $sqlTail);
        $use_ret = $mysql->query("SELECT COUNT(*) FROM cloud_regcode WHERE user_id = '$userId' AND beginuse_time IS NOT NULL AND overdue = 0" . $sqlTail);
        $online_ret = $mysql->query("SELECT COUNT(*) FROM cloud_regcode WHERE user_id = '$userId' AND isonline = 1" . $sqlTail);
        $frozen_ret = $mysql->query("SELECT COUNT(*) FROM cloud_regcode WHERE user_id = '$userId' AND frozen = 1" . $sqlTail);
        $this->assign('unuse_count', $unuse_ret[0]['count(*)']);
        $this->assign('use_count', $use_ret[0]['count(*)']);
        $this->assign('online_count', $online_ret[0]['count(*)']);
        $this->assign('frozen_count', $frozen_ret[0]['count(*)']);

    }

}