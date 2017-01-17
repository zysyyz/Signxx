<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

class CreatecodeController extends BaseController {
    private $_softArray;

    public function index(){
        $this->assign('createcode_selected', 'selected');
        $this->init();
        $this->display();
    }

    private function init() {
        $this->_softArray = $this->getSoftwareList();
        if ($this->_softArray == '') {
            HJCTool::alertBack('您还没有新建软件');
        } else {
            $this->assign('softlist', $this->_softArray);
        }

        if (isset($_GET['produce'])) {
            $this->produceCode();
        }
    }

    private function produceCode() {
        if ($this->isEmpty(I('softname'))) {
            HJCTool::alertBack('请先新建一个软件!');
        }
        if ($this->isEmpty(I('count','','/^[1-9]\d*$/'))) {
            HJCTool::alertBack('生成注册码张数必须大于0');
        }
        if ($this->isEmpty(I('time','','/^[1-9]\d*$/'))) {
            HJCTool::alertBack('使用时间必须大于0');
        }
        $all_minutes;
        $timeStr;
        if (I('time_type') == 'day') {
            $all_minutes = I('time') * 60 * 24;
            $timeStr = I('time') . '天';
        } else {
            $all_minutes = I('time') * 60;
            $timeStr = I('time') . '小时';
        }

        $softId = 0;
        foreach ($this->_softArray as $arr) {
            if ($arr['name'] == I('softname')) {
                $softId = $arr['id'];
                break;
            }
        }
        if ($softId == 0) {
            HJCTool::alertBack('不是有效软件');
        }

        $secret = new Secret();
        $mysql = M('Regcode');
        $codes = array();
        $time = date('Y-m-d H:i:s',time());
        $softname = I('softname');
        $userId = $this->getUserId();
        $mark = I('mark');

        $ret = $mysql->query("SELECT COUNT(*) FROM cloud_regcode WHERE user_id = '$userId' AND beginuse_time IS NULL");
        $unUse = $ret[0]['count(*)'];
        $max = 100;
        if ($unUse + I('count') > $max) {
            HJCTool::alertBack('未使用注册码张数不能超过' . $max . '张,您最多还可以生成' . ($max - $unUse) . '张');
        }

        for ($i = 0; $i<I('count'); $i++) {
            $code = $secret->createRandRegisterCode();
            $soft_ret = $mysql->execute("INSERT INTO cloud_regcode (code, user_id, software_id, all_minutes, produce_time, time_str, software_name, mark) VALUES ('{$code}', '{$userId}', '{$softId}', {$all_minutes}, '{$time}', '{$timeStr}', '{$softname}', '$mark')
");
            if (!$soft_ret) {
                HJCTool::alertBack('请刷新页面后重试');
            }

            if (I('creat_type') == 0) { // 注册码
                array_push($codes, $code);
            } else { // 注册码+推荐码
                $ret = $mysql->query("SELECT *FROM cloud_regcode WHERE code='$code'");
                array_push($codes, '注册码:' . $code . ' 推荐码:' . ($ret[0]['id'] + 10000));
            }
        }

        $this->assign('code_list', $codes);
    }


}