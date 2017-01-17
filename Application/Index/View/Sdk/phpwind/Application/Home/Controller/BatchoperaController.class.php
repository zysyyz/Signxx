<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class BatchoperaController extends BaseController {
    public function index(){
        $this->assign('batchopera_selected', 'selected');
        $this->display();

        if (I('codes') == '') return;

        $arr = explode("\n", I('codes'));

        $sql = '';
        foreach($arr as $u){
            $strarr = explode("，",$u);
            foreach($strarr as $newstr){
                $newstr = trim($newstr);
                if ($newstr != '') {
                    $sql = $sql . " OR code = '" . $newstr . "' ";
                }
            }
        }
        $sql = HJCTool::strReplaceOnce($sql, 'OR', '', 1);
        $sql = ' AND (' . $sql . ')';

        $userId = $this->getUserId();
        $limitUserSql = " WHERE user_id = '$userId'";

        $mysql = M('Regcode');
        $updateret;
        if (I('op') == 'unbind') {
            $updateret = $mysql->execute("UPDATE cloud_regcode SET computer_uid = '' " .$limitUserSql. $sql);
        } elseif (I('op') == 'frozen') {
            $updateret = $mysql->execute("UPDATE cloud_regcode SET frozen = 1 " .$limitUserSql. $sql);
        } else if (I('op') == 'del') {
            $updateret = $mysql->execute("DELETE FROM cloud_regcode " .$limitUserSql. $sql);
        }

        if (I('op') != '') {
            if ($updateret) {
                HJCTool::alertToLocation('操作完成', 'batchopera?codes=' . I('codes'));
            } else {
                HJCTool::alertToLocation('操作失败', 'batchopera?codes=' . I('codes'));
            }
        }
    }
}