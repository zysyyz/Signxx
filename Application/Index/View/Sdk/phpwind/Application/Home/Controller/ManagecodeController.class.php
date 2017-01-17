<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class ManagecodeController extends BaseController {
    private $finalSql;

    public function index() {
        $this->assign('managecode_selected', 'selected');
        // 把当前页数传到tpl,在请求的时候可以带过来,以便删除商品后刷新在当前页
        $this->assign('querystr', $_SERVER[REDIRECT_QUERY_STRING]);
        $this->init();
        $this->display();
    }

    public function codedetail() {
        $this->assign('managecode_selected', 'selected');
        $this->codedetailHandle();
    }

    private function codedetailHandle(){
        if (I('code') == '') return;

        $code = I('code');
        $userId = $this->getUserId();
        $mysql = M('Regcode');
        $ret = $mysql->query("SELECT * FROM cloud_regcode WHERE code = '$code' AND user_id = '$userId'");
        // 注册码没找到
        if (!$ret) {
            return;
        }

        $this->assign('software_name', $ret[0]['software_name']);
        $this->assign('time_str', $ret[0]['time_str']);
        $this->assign('produce_time', $ret[0]['produce_time']);
        $this->assign('beginuse_time', $ret[0]['beginuse_time']);
        $this->assign('expire_time', $ret[0]['expire_time']);
        $this->assign('last_time', $ret[0]['last_time']);
        $this->assign('last_ip', $ret[0]['last_ip']);
        $this->assign('use_count', $ret[0]['use_count']);
        $this->assign('online', $ret[0]['isonline']==0?'不在线':'在线');
        $this->assign('bindtype', $ret[0]['computer_uid'] =='' ? '未绑定任何电脑':'已绑定电脑');
        $this->assign('frozen', $ret[0]['frozen']);
        $this->assign('mark', $ret[0]['mark']);

        $this->display();

        if (I('code') != '') {
            if (isset($_GET['unbind'])) {
                $this->unbind();
            }
            if (isset($_GET['change'])) {
                $this->change();
            }
        }
    }

    private function unbind() {
        $code = I('code');
        $mysql = M('Regcode');
        $mysql->execute("UPDATE cloud_regcode SET computer_uid = '', isonline = 0 WHERE code = '{$code}' AND computer_uid != ''");
        HJCTool::alertToLocation('解绑成功', 'codedetail?code=' . $code);
    }

    private function change() {
        $code = I('code');
        $frozen = I('frozen');
        $mark = I('mark');
        $mysql = M('Regcode');
        $updateret = $mysql->execute("UPDATE cloud_regcode SET frozen = '$frozen', mark = '$mark' WHERE code = '{$code}'");
        if ($updateret) {
            HJCTool::alertToLocation('修改成功', 'codedetail?code=' . $code);
        } else {
            HJCTool::alertToLocation('修改失败', 'codedetail?code=' . $code);
        }
    }

    private function init() {
        $softwares = $this->getSoftwareList();
        if ($softwares == '') {
            HJCTool::alertBack('您还没有新建软件');
        } else {
            $this->assign('softlist', $softwares);
        }

        // 删除注册码
        if (I('delcode') != '') {
            $this->deleteCode();
        }

        // 导出txt
        if (I('op') == 'export1' || I('op') == 'export2') {
            $this->exportTxt(I('op'));
        }

        if (!isset($_GET['search'])) {
            // 默认搜索全部
            parent::showTableAndPage('regcode');
            return;
        }
        $this->searchHandle();
    }

    // 导出txt文本
    private function exportTxt($op) {
        $today = date('Ymd');
        $filename='Signxx_' . $today . '.txt';//要导出的文件的文件名需要加上文件后缀

        $mysql = M();
        if ($this->finalSql) {
            $ret = $mysql->query($this->finalSql);
        } else {
            $userId = $this->getUserId();
            $ret = $mysql->query("SELECT *FROM cloud_regcode WHERE user_id = '$userId'");
        }
        foreach ($ret as $json) {
            if ($op == 'export1') { //注册码和推荐码
                $chars = $chars . '注册码:' . $json['code'] . ' 推荐码:' . ($json['id'] + 10000) . "\r\n";
            } else { // 注册码
                $chars = $chars . $json['code'] . "\r\n";
            }
        }

        header('Content-Type: text/x-sql');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment; filename="' .$filename. '"');
        $is_ie = 'IE';
        if ($is_ie == 'IE') {
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Pragma: no-cache');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        }
        echo $chars;
        exit();
    }

    private function deleteCode(){
        $code = I('delcode');
        $userId = $this->getUserId();
        $mysql = M('Regcode');
        $updateret = $mysql->execute("DELETE FROM cloud_regcode WHERE code = '$code' AND user_id = '$userId'");
        if ($updateret) {
            if (I('page') == '') {
                HJCTool::alertToLocation('删除成功', '/Home/managecode');
            } else {
                HJCTool::alertToLocation('删除成功', '/Home/managecode?page=' . I('page'));
            }
        } else {
            HJCTool::alertBack('删除失败');
        }
    }

    private function searchHandle() {
        // 注册码sql
        $codeSql = I('regcode') == '' ? '' : " AND code = '" . I('regcode') . "'";

        // 时间范围sql
        $timerangetype;
        switch (I('timerangetype')) {
            case 1;
                $timerangetype = 'expire_time';
                break;
            case 2;
                $timerangetype = 'produce_time';
                break;
            case 3;
                $timerangetype = 'beginuse_time';
                break;
            case 4;
                $timerangetype = 'last_time';
                break;
        }
        $timerangeSql = '';
        if (!$this->isEmpty(I('timerangefrom')) && !$this->isEmpty(I('timerangeto'))) {
            $timerangeSql = " AND " . $timerangetype . " BETWEEN '" . I('timerangefrom') . "' AND '" . I('timerangeto') . "'";
        } elseif (!$this->isEmpty(I('timerangefrom')) && $this->isEmpty(I('timerangeto'))) {
            $timerangeSql = " AND " . $timerangetype . " > '" . I('timerangefrom') . "'";
        } elseif ($this->isEmpty(I('timerangefrom')) && !$this->isEmpty(I('timerangeto'))) {
            $timerangeSql = " AND " . $timerangetype . " < '" . I('timerangeto') . "'";
        }

        // 软件名sql
        $softwareSql = I('softname') == '' ? '' : " AND software_name = '" . I('softname') . "'";

        // 时长sql
        $timeStr = I('time_type') == 'day' ? I('time') . '天' : I('time') . '小时';
        $timeSql = I('time') == '' ? '' : " AND time_str = '" . $timeStr . "'";

        // 是否在线sql, 全勾选或不勾都是全部搜索
        $onlineSql = '';
        if ($this->isEmpty(I('online_yes')) && !$this->isEmpty(I('online_no'))) {
            $onlineSql = " AND isonline = 0";
        } elseif (!$this->isEmpty(I('online_yes')) && $this->isEmpty(I('online_no'))) {
            $onlineSql = " AND isonline = 1";
        }

        // 是否过期sql
        $expireSql = '';
        if ($this->isEmpty(I('expire_yes')) && !$this->isEmpty(I('expire_no'))) {
            $expireSql = " AND overdue = 0";
        } elseif (!$this->isEmpty(I('expire_yes')) && $this->isEmpty(I('expire_no'))) {
            $expireSql = " AND overdue = 1";
        }

        // 是否使用sql
        $inuseSql = '';
        if ($this->isEmpty(I('inuse_yes')) && !$this->isEmpty(I('inuse_no'))) {
            $inuseSql = " AND beginuse_time IS NULL";
        } elseif (!$this->isEmpty(I('inuse_yes')) && $this->isEmpty(I('inuse_no'))) {
            $inuseSql = " AND beginuse_time IS NOT NULL";
        }

        $allSql = $codeSql . $timerangeSql . $softwareSql . $timeSql . $onlineSql . $expireSql . $inuseSql;
        if (strlen($allSql) > 0) {
            $userId = $this->getUserId();
            $this->finalSql = "SELECT * FROM cloud_regcode  WHERE user_id = '$userId' AND " . $allSql;
            $this->finalSql = HJCTool::strReplaceOnce($this->finalSql, 'AND', '', 1);
            parent::showTableAndPage('regcode', $this->finalSql);
        } else {
            // 没有搜索条件,搜索全部
            parent::showTableAndPage('regcode');
        }

    }

}