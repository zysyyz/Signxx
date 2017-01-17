<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class ModifyPasswordController extends BaseController {
    private $_softwares;

    public function index() {
        $this->init();
        $this->display();
    }

    private function init() {
        if (I('oldpwd') == '' || I('newpwd') == '') {
            return;
        }
        if (I('newpwd', '', '/^[a-z0-9A-Z]{6,16}$/') == '') {
            HJCTool::alertBack('密码格式错误: 请输入6-16位英文字母或数字');
        }
        if (I('oldpwd') == I('newpwd')) {
            HJCTool::alertBack('新密码不可和旧密码相同');
        }

        $oldpwd = HJCTool::secret(I('oldpwd'));
        $newpwd = HJCTool::secret(I('newpwd'));
        $userId = $this->getUserId();
        $mysql = M();
        $ret = $mysql->query("SELECT * FROM cloud_user WHERE id = '$userId' AND password = '$oldpwd'");
        if ($ret) {
            $updateret = $mysql->execute("UPDATE cloud_user SET password = '$newpwd' WHERE id = '$userId'");
            if ($updateret) {
                // 修改成功退出登录
                HJCTool::alertToLocation('修改成功,请重新登录!', '/Home/?a=logout');
            } else {
                HJCTool::alertBack('修改失败!');
            }
        } else {
            HJCTool::alertBack('密码不正确');
        }
    }
}