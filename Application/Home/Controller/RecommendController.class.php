<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;

class RecommendController extends BaseController {
    public function index() {
        $this->assign('recommend_selected', 'selected');
        $this->init();
        $this->display();
    }

    private function init() {
        $userId = $this->getUserId();
        // 读取规则
        $mysql = M();
        $ret = $mysql->query("SELECT *FROM cloud_recommenderrule WHERE user_id = $userId");
        $this->assign('recommenderRules', $ret);
        $ret = $mysql->query("SELECT *FROM cloud_recommendedrule WHERE user_id = $userId");
        $this->assign('recommendedRules', $ret);

        if (I('op') == 'save') {
            $this->saveRule();
        }
    }

    private function saveRule() {
        $userId = $this->getUserId();

        // 推荐人规则
        $recommenderRules = I('recommenderRule');
        if (sizeof($recommenderRules) > 0) {
            $mysql = M();
            // 先删除用户下的全部规则,再插入新的
            $mysql->execute("DELETE FROM cloud_recommenderrule WHERE user_id = '$userId'");
            foreach ($recommenderRules as $rule) {
                $originDay = $rule['originDay'];
                $giveDay = $rule['giveDay'];
                if (preg_match ("/^\d+$/", $originDay) && preg_match ("/^\d+$/", $giveDay)) {
                    $mysql->execute("INSERT INTO cloud_recommenderrule (user_id, origin_day, give_day) VALUES ($userId, $originDay, $giveDay)");
                } else {
                    HJCTool::alertBack('请输入正整数');
                }
            }
        }

        // 被推荐人规则
        $recommendedRules = I('recommendedRule');
        if (sizeof($recommendedRules) > 0) {
            $mysql = M();
            // 先删除用户下的全部规则,再插入新的
            $mysql->execute("DELETE FROM cloud_recommendedrule WHERE user_id = '$userId'");
            foreach ($recommendedRules as $rule) {
                $originDay = $rule['originDay'];
                $giveDay = $rule['giveDay'];
                if (preg_match ("/^\d+$/", $originDay) && preg_match ("/^\d+$/", $giveDay)) {
                    $mysql->execute("INSERT INTO cloud_recommendedrule (user_id, origin_day, give_day) VALUES ($userId, $originDay, $giveDay)");
                } else {
                    HJCTool::alertBack('请输入正整数');
                }
            }
        }

        // 刷新页面
        HJCTool::alertToLocation(null, '/Home/recommend');
    }
}
