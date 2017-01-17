<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class ManagesoftController extends BaseController {

    public function index(){
        $this->assign('managesoft_selected', 'selected');
        // 把当前页数传到tpl,在请求的时候可以带过来,以便删除商品后刷新在当前页
        $this->assign('querystr', $this->_lastQueryStr);

        parent::showTableAndPage('software');
        $this->deleteSoft();
        $this->display();

    }

    private function deleteSoft(){
        if (I('delsid') == '') return;

        $sid = I('delsid');
        $userId = $this->getUserId();
        $mysql = M('Software');
        $updateret = $mysql->execute("DELETE FROM cloud_software WHERE id = '$sid' AND user_id = '$userId'");
        if ($updateret) {
            // 删除对应的注册码
            $mysql = M('Regcode');
            $updateret = $mysql->execute("DELETE FROM cloud_regcode WHERE software_id = '$sid' AND user_id = '$userId'");
            // 不去判断有没有删除成功了,没有注册码会返回flase
            if (I('page') == '') {
                HJCTool::alertToLocation('删除成功', 'managesoft');
            } else {
                HJCTool::alertToLocation('删除成功', 'managesoft?page=' . I('page'));
            }
        } else {
            HJCTool::alertBack('删除失败');
        }
    }
}