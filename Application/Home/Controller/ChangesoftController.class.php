<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class ChangesoftController extends BaseController {
    private $_softwareName;

    public function index() {
        $this->assign('managesoft_selected', 'selected');

        // 修改软件
        $softid = I('sid');
        if ($softid != '') {
            $this->assign('buttonTitle', '修改');
            $userId = $this->getUserId();
            $mysql = M('Software');
            $ret = $mysql->query("SELECT * FROM cloud_software WHERE id = $softid AND user_id = '$userId'");
            // 软件没找到
            if (!$ret) {
                HJCTool::alertBack('未找到软件');
            }
            $this->_softwareName = $ret[0]['name'];
            $this->assign('sid', $softid);
            $this->assign('software', $ret[0]['name']);
            $this->assign('try_minutes', $ret[0]['try_minutes']);
            $this->assign('try_count', $ret[0]['try_count']);
            $this->assign('bindmode' . $ret[0]['bindmode'], 'checked');
            $this->assign('unbindmode' . $ret[0]['unbindmode'], 'checked');
            $this->assign('frozen' . $ret[0]['frozen'], 'checked');
            $this->assign('updatemode' . $ret[0]['updatemode'], 'checked');
            $this->assign('version', $ret[0]['version']);
            $this->assign('update_url', $ret[0]['update_url']);
            $this->assign('info', $ret[0]['info']);
        } else {
            $this->assign('buttonTitle', '新建');
            $this->assign('try_minutes', 30);
            $this->assign('try_count', 2);
            $this->assign('bindmode0', 'checked');
            $this->assign('unbindmode0', 'checked');
            $this->assign('frozen0', 'checked');
            $this->assign('updatemode0', 'checked');
            $this->assign('version', 1);
        }
        $this->change();
        $this->display();
    }

    private function change() {
        if (!isset($_GET['submit'])) {
            return;
        }

        if (trim(I('software')) != '') {
            $software = trim(I('software'));
        } else {
            HJCTool::alertBack('软件名不能为空');
        }

        // 判断软件名是否存在
        $user_id = $this->getUserId();
        $mysql = M('Software');
        if (I('sid') == '') { // 新建
            $ret = $mysql->query("SELECT * FROM cloud_software WHERE user_id = '$user_id'");
            if ($ret) {
                foreach ($ret as $dic) {
                    if ($software == $dic['name']) {
                        HJCTool::alertBack('软件名已存在');
                    }
                }
            }
        } else { // 修改
            // 搜索的时候排除当前id的软件名,和其他软件名比较
            $ret = $mysql->query("SELECT * FROM cloud_software WHERE user_id = '$user_id' AND name != '{$this->_softwareName}'");
            if ($ret) {
                foreach ($ret as $dic) {
                    if ($software == $dic['name']) {
                        HJCTool::alertBack('软件名已存在');
                    }
                }
            }
        }

        if (I('try_minutes', '', '/^\d+$/') != '') {
            $try_minutes = I('try_minutes');
        } else {
            HJCTool::alertBack('试用分钟必须大于等于0');
        }
        if (I('try_count', '', '/^\d+$/') != '') {
            $try_count = I('try_count');
        } else {
            HJCTool::alertBack('试用次数必须大于等于0');
        }
        if (I('bindmode', '', '/^\d+$/') != '') {
            if (I('bindmode') > 2) {
                HJCTool::alertBack('请选择一个绑机多开模式');
            }
            $bindmode = I('bindmode');
        } else {
            HJCTool::alertBack('请选择一个绑机多开模式');
        }
        if (I('unbindmode', '', '/^\d+$/') != '') {
            if (I('unbindmode') > 1) {
                HJCTool::alertBack('请选择一个用户解绑模式');
            }
            $unbindmode = I('unbindmode');
        } else {
            HJCTool::alertBack('请选择一个用户解绑模式');
        }
        if (I('frozen', '', '/^\d+$/') != '') {
            if (I('frozen') > 1) {
                HJCTool::alertBack('请选择一个软件状态');
            }
            $frozen = I('frozen');
        } else {
            HJCTool::alertBack('请选择一个软件状态');
        }
        if (I('updatemode', '', '/^\d+$/') != '') {
            if (I('updatemode') > 1) {
                HJCTool::alertBack('请选择一个更新模式');
            }
            $updatemode = I('updatemode');
        } else {
            HJCTool::alertBack('请选择一个更新模式');
        }
        if (I('version', '', '/^\d+$/') != '') {
            $version = I('version');
        } else {
            HJCTool::alertBack('软件版本为非负整数');
        }
        $update_url = I('update_url');
        $info = str_replace(array("\r\n", "\r", "\n"), "", I('info'));
        $create_time = date('Y-m-d H:i:s', time());

        if (I('sid') == '') { // 新建
            $updateret = $mysql->execute("INSERT INTO cloud_software (create_time, user_id, name, try_minutes, try_count, bindmode, unbindmode, version, update_url, updatemode, info, frozen) VALUES ('$create_time', '$user_id', '$software', '$try_minutes', '$try_count', '$bindmode', '$unbindmode', '$version', '$update_url', '$updatemode', '$info', '$frozen')");
            if (!$updateret) {
                HJCTool::alertBack('新建失败');
            } else {
                HJCTool::alertToLocation('新建成功', 'managesoft');
            }
        } else { // 修改
            $sid = I('sid');
            $updateret = $mysql->execute("UPDATE cloud_software SET user_id = '$user_id', name = '$software', try_minutes = '$try_minutes', try_count = '$try_count', bindmode = '$bindmode',unbindmode = '$unbindmode',version = '$version', update_url = '$update_url',updatemode = '$updatemode',info = '$info',frozen ='$frozen' WHERE id = '$sid'");
            if (!$updateret) {
                HJCTool::alertBack('修改失败');
            } else {
                // 这里是为了修改软件名的同时修改注册码表的软件名,但是如果软件名没有改,这句就会返回失败,所以不用管
                $mysql = M('Regcode');
                $mysql->execute("UPDATE cloud_regcode SET software_name = '$software' WHERE software_id = '$sid'");
                HJCTool::alertToLocation('修改成功', 'managesoft');
            }
        }

    }

}