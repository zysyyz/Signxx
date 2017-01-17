<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Home\Tool\HJCTool;
use Home\Tool\Secret;

// 引入成功了
require getcwd() . '/Application/Home/Common/Rsa/BigInteger.php';
require getcwd() . '/Application/Home/Common/Rsa/rsa.php';
require getcwd() . '/Application/Home/Common/Des/DES.php';

class AuthController extends BaseController {
    private $_param;
    private $_jsonArr;
    private $_modulus;
    private $_p;
    private $_keylength;
    private $_mend;

    public function __construct() {
        parent::__construct();

        $this->_modulus = 'AEDA765C9BF7C09E6CC5639C94E4A5772A719337290BB0CF496EA5100877760F4D33D30AAB12BDCFE1471AD184C37F8AAB1E8FC3A128CEDE6B3D98CCFE21492D508AEEE8775DE42E407758E212BA238FD1CF5F78226F10F0BD6060FD70390CB572F8CE1A74D9AA31F4FB45372599862EF8F767BFFA6F59E0A54FD0E1977ECB89';
        $this->_p = "0DD93623B39CE424ADB2DC01AB305B154ED8AF868E2395083E36AE966D9F555000DCF995E3154AE6F1B97A4D368E4F9F0871ED66B3A554040DEBE19A98A309EA52096E58BF2507EFAD83C850294EE85B7BF68FC5455AB5BB302E9AD68B838FF9B6E9D78DBBB91EA9AF52B5D82D521E87587EB4C094B3D0AA09A6BCB795D0F587";
        $this->_keylength = 1024;
        $this->_modulus_16 = new \Math_BigInteger($this->_modulus, 16);
        $this->_mend = $this->_modulus_16->toString();
        $this->_p_16 = new \Math_BigInteger($this->_p, 16);
        $this->_private_key = $this->_p_16->toString();
    }

    private function traceHttp() {
        $content = "## 来访时间：" . date('Y-m-d H:i:s') . " IP：" . $_SERVER["REMOTE_ADDR"] . "\n## 请求字段密文：" . $_SERVER['QUERY_STRING'] . "\n## 请求字段解密：" . json_encode($this->_jsonArr) . "\n";

        if (isset($_SERVER['HTTP_APPNAME'])) {    // SAE
            sae_set_display_errors(false);
            sae_debug(trim($content));
            sae_set_display_errors(true);
        } else {
            $today = date('Ymd');
            $log_filename = 'file/' . $today . '.log';
            file_put_contents($log_filename, $content, FILE_APPEND);
        }
    }

    private function beforeHandle() {
        // 强制下线当天没请求过的注册码
        $this->searchIsOnline();

        // 参数加密在data字段里
        if (!isset($_GET['data'])) {
            $this->returnJson('100', '非法参数!');
        }

        $data = $_GET['data'];

        $pos = strpos($data,"#");
        if ($pos === false) {
            $d = rsa_decrypt(hex2bin($data), $this->_private_key, $this->_mend, $this->_keylength);
            // 反向
            $unsecret = strrev($d);
            $this->_jsonArr = json_decode($unsecret);
        } else { // 分段解密
            $dataL = substr($data, 0, $pos);
            $dataR = substr($data, $pos + 1);

            $dL = rsa_decrypt(hex2bin($dataL), $this->_private_key, $this->_mend, $this->_keylength);
            $unsecretL = strrev($dL);

            $dR = rsa_decrypt(hex2bin($dataR), $this->_private_key, $this->_mend, $this->_keylength);
            $unsecretR = strrev($dR);

            $this->_jsonArr = json_decode($unsecretL . $unsecretR);
        }

        $this->traceHttp();

        // 机器码不能空
        $this->isComputerIdValid();
    }

    // 注册码注册
    public function signin() {
        $this->beforeHandle();

        // op=操作,0注册,1解绑,3试用,4下线
        switch ($this->_jsonArr->o) {
            case 0:
                $this->reg();
                break;
            case 1:
                $this->unbind();
                break;
            case 3:
                $this->trial();
                break;
            case 4:
                $this->offline();
                break;
            case 5:
                $this->recommend();
                break;
        }
    }

    // check校验分开处理 因为1分钟内最多验证一次 免费的解密计算会浪费cpu资源
    public function valid() {
        $this->check();
    }

    // 推荐码加时
    // 每个注册码只能填一次推荐码(加一次时),推荐人可以一直加时
    private function recommend() {
        $softret = $this->isSoftwareFrozen();
        $ret = $this->isCodeValid();

        // 先查这个注册码有没有填过推荐码
        if ($ret[0]['recommend_code']) {
            $this->returnJson('-1', '已经获得过加时了!推荐码是' . $ret[0]['recommend_code']);
        }

        // 根据推荐码得到注册码id
        $code_id = $this->_jsonArr->rm - 10000;

        if ($code_id == $ret[0]['id']) {
            $this->returnJson('-1', '推荐人不能是自己');
        }

        // 根据注册码id获取推荐人的用户id
        $mysql = M();
        $code_ret = $mysql->query("SELECT *FROM cloud_regcode WHERE id = '$code_id'");
        if (!$code_ret) {
            $this->returnJson('-1', '推荐码不正确');
        }

        if ($code_ret[0]['recommend_code'] - 10000 == $ret[0]['id']) {
            $this->returnJson('-1', '不允许互相推荐');
        }

        $recommender_user_id = $code_ret[0]['user_id'];

        // 查询被推荐人规则
        $recommendedRule = $mysql->query("SELECT *FROM cloud_recommendedrule WHERE user_id = '$recommender_user_id'");
        if (!$recommendedRule) {
            $this->returnJson('-1', '被推荐人奖励未设置');
        }
        $success = false;
        foreach ($recommendedRule as $rule) {
            // 更新被推荐人的推荐码、到期时间、总共时间、时间文本
            if ($ret[0]['all_minutes'] / 60 / 24 == $rule['origin_day']) {
                $code = $this->_jsonArr->c;
                $giveDay = $rule['give_day'];
                $new_all_minutes = $ret[0]['all_minutes'] + $giveDay * 24 * 60;
                $new_expire_time = date('Y-m-d H:i:s', strtotime($ret[0]['expire_time']) + $giveDay * 24 * 60 * 60);
                $new_time_str = (explode('天', $ret[0]['time_str'])[0] + $giveDay) . '天';
                $recommend_code = $this->_jsonArr->rm;
                if ($ret[0]['expire_time']) { // 注册码已使用
                    $ret = $mysql->execute("UPDATE cloud_regcode SET all_minutes = '$new_all_minutes', expire_time = '$new_expire_time', time_str = '$new_time_str', recommend_code = '$recommend_code' WHERE code = '$code'");
                } else { // 注册码未使用
                    $ret = $mysql->execute("UPDATE cloud_regcode SET all_minutes = '$new_all_minutes', time_str = '$new_time_str', recommend_code = '$recommend_code' WHERE code = '$code'");
                }
                if (!$ret) {
                    $this->returnJson('-1', '被推荐人加时出现异常');
                }
                $success = true;
                break;
            }
        }

        // 查询推荐人规则
        $recommenderRule = $mysql->query("SELECT *FROM cloud_recommenderrule WHERE user_id = '$recommender_user_id'");
        if ($recommenderRule) {
            foreach ($recommenderRule as $rule) {
                // 更新被推荐人的推荐码、到期时间、总共时间、时间文本
                if ($code_ret[0]['all_minutes'] / 60 / 24 == $rule['origin_day']) {
                    $giveDay = $rule['give_day'];
                    $new_all_minutes = $code_ret[0]['all_minutes'] + $giveDay * 24 * 60;
                    $new_expire_time = date('Y-m-d H:i:s', strtotime($code_ret[0]['expire_time']) + $giveDay * 24 * 60 * 60);
                    $new_time_str = (explode('天', $code_ret[0]['time_str'])[0] + $giveDay) . '天';
                    $recommend_code = $this->_jsonArr->rm;
                    if ($code_ret[0]['expire_time']) { // 注册码已使用
                        $mysql->execute("UPDATE cloud_regcode SET all_minutes = '$new_all_minutes', expire_time = '$new_expire_time', time_str = '$new_time_str' WHERE id = '$code_id'");
                    } else { // 注册码未使用
                        $mysql->execute("UPDATE cloud_regcode SET all_minutes = '$new_all_minutes', time_str = '$new_time_str' WHERE id = '$code_id'");
                    }
                    break;
                }
            }
        }
        if ($success) {
            $this->returnJson('0', '加时成功!');
        } else {
            $this->returnJson('-1', '加时失败!注册码时长未达到要求');
        }

    }

    // 注册:登录注册码修改数据库信息
    private function reg() {
        $softret = $this->isSoftwareFrozen();
        $ret = $this->isCodeValid();

        // 机器码是否改变
        if ($ret[0]['computer_uid'] != '') {
            // 只有绑机单开模式才需要判断机器码相不相同
            if ($softret[0]['bindmode'] == '0') {
                if ($ret[0]['computer_uid'] != $this->_jsonArr->ci) {
                    $this->returnJson('-1', '登录失败,该注册码已绑定其他电脑!');
                }
            }
        }

        // 判断注册码和软件编号是否对应
        if ($ret[0]['software_id'] != $this->_jsonArr->si) {
            $this->returnJson('-1', '登录失败,注册码不属于该软件编号!');
        }

        // 修改注册码的到期时间、机器码、登录时间、登录ip、登录次数、在线
        $hours = $ret[0]['all_minutes'] / 60;
        $expire_time = $ret[0]['expire_time'];
        $computer_uid = $this->_jsonArr->ci;
        $last_time = date('Y-m-d H:i:s', time());
        $last_ip = $this->_jsonArr->ip;
        $use_count = $ret[0]['use_count'] + 1;

        // 绑机单开:机器码+注册码+请求时间戳
        // 绑机多开:机器码+注册码
        // 不绑机多开:注册码
        switch ($softret[0]['bindmode']) {
            case '0':
                $token = substr(md5($computer_uid . $this->_jsonArr->c . time()), 0, 5);
                break;
            case '1':
                $token = substr(md5($computer_uid . $this->_jsonArr->c), 0, 5);
                break;
            case '2':
                $token = substr(md5($this->_jsonArr->c), 0, 5);
                break;
        }

        $update_ret;
        $mysql = M('Software');
        $code = $this->_jsonArr->c;
        // 如果已经在使用了,就不修改beginuse_time字段
        if ($ret[0]['beginuse_time'] == '') {
            $beginuse_time = date('Y-m-d H:i:s', time());
            $expire_time = date('Y-m-d H:i:s', strtotime("+{$hours} hour"));
            $update_ret = $mysql->execute("UPDATE cloud_regcode SET beginuse_time = '$beginuse_time', expire_time = '$expire_time', computer_uid = '$computer_uid', last_time = '$last_time', last_ip = '$last_ip', use_count = 1, isonline = 1, token = '$token' WHERE code = '$code'");
        } else {
            $update_ret = $mysql->execute("UPDATE cloud_regcode SET computer_uid = '$computer_uid', last_time = '$last_time', last_ip = '$last_ip', use_count = '$use_count', isonline = 1, token = '$token' WHERE code = '$code'");
        }

        if (!$update_ret) {
            $this->returnJson('105', '登录异常!');
        }

        // 如果请求的版本小于设置的版本
        if ($this->_jsonArr->v != '' && $this->_jsonArr->v < $softret[0]['version']) {
            if ($softret[0]['updatemode'] == '1') { // 强制更新
                $_SESSION['lastCheckRet'] = '-2';
                $this->_param['msg'] = '[登录结果]:登录成功\n[软件公告]:' . $softret[0]['info'] . '\n[软件版本]:有新版本' .$softret[0]['version']. ',请更新!\n[到期时间]:' . $expire_time;
            } else {
                $_SESSION['lastCheckRet'] = '0';
                $this->_param['msg'] = '[登录结果]:登录成功\n[软件公告]:' . $softret[0]['info'] . '\n[软件版本]:有新版本' .$softret[0]['version']. ',请更新!\n[到期时间]:' . $expire_time;
            }

        } else {
            $_SESSION['lastCheckRet'] = '0';
            $this->_param['msg'] = '[登录结果]:登录成功\n[软件公告]:' . $softret[0]['info'] . '\n[软件版本]:已是最新版本\n[到期时间]:' . $expire_time;
        }

        $_SESSION['checkFormalOrTrial'] = 'formal';
        $this->_param['error'] = $_SESSION['lastCheckRet'];
        $this->_param['token'] = $token;
        $this->_param['url'] = $softret[0]['update_url'];
        $this->_param['t'] = $this->_jsonArr->t;
        $this->secretReturn($this->_param);

    }

    // 解绑:删除机器码并下线
    private function unbind() {
        $softret = $this->isSoftwareFrozen();
        $ret = $this->isCodeValid();

        if ($softret[0]['unbindmode'] == '1') {
            $this->returnJson('-1', '解绑失败,该软件不允许用户解绑!');
        }

        if ($ret[0]['computer_uid'] != '') {
            if ($ret[0]['computer_uid'] != $this->_jsonArr->ci) {
                $this->returnJson('-1', '解绑失败,只能在原电脑解绑!');
            }
        } else {
            $this->returnJson('-1', '解绑失败,已经解绑过了!');
        }

        $update_ret;
        $mysql = M('Regcode');
        $code = $this->_jsonArr->c;
        $update_ret = $mysql->execute("UPDATE cloud_regcode SET computer_uid = '', isonline = 0 WHERE code = '{$code}'");
        if (!$update_ret) {
            $this->returnJson('106', '解绑失败!');
        } else {
            $this->returnJson('0', '解绑成功!');
        }

    }

    // 1分钟内最多请求一次放到客户端处理, 因为t参数需要对比, 不解析请求数据的话无法获得t
    private function check() {
        $this->beforeHandle();

        // 强制更新检查
        $softret = $this->isSoftwareFrozen();
        if ($this->_jsonArr->v != '' && $this->_jsonArr->v < $softret[0]['version'] && $softret[0]['updatemode'] == '1') {
            $this->offline();
            $_SESSION['lastCheckRet'] = '-2';
            $this->_param['error'] = $_SESSION['lastCheckRet'];
            $this->_param['msg'] = '请更新最新版本' . $softret[0]['version'];
            $this->_param['url'] = $softret[0]['update_url'];
            $this->_param['t'] = $this->_jsonArr->t;
            $this->secretReturn($this->_param);
        }

        // formal正式,trial试用
        if ($_SESSION['checkFormalOrTrial'] == 'formal') {
            $ret = $this->isCodeValid();

            // 机器码空就是刚被解绑
            if ($ret[0]['computer_uid'] == '') {
                $this->offline();
                $this->returnJson('-1', '验证不通过,请重新注册!');
            }

            // token是否改变
            if ($ret[0]['token'] != $this->_jsonArr->k) {
                $this->returnJson('-1', '验证不通过,禁止多开!');
            }

            // 多开时关闭部分窗口会下线,验证通过重新上线,不用判断上线是否成功
            $mysql = M('Regcode');
            $code = $this->_jsonArr->c;
            $update_ret = $mysql->execute("UPDATE cloud_regcode SET isonline = 1 WHERE code = '{$code}'");

            $this->returnJson('0', '验证通过');
        } else {
            $computer_uid = $this->_jsonArr->ci;
            $softid = $softret[0]['id'];

            // 获取使用记录,原则上说不可能取不到因为第一次试用会保存到数据库
            $trialsql = M('Trial');
            $trialret = $trialsql->query("SELECT * FROM cloud_trial WHERE computer_uid = '$computer_uid' AND software_id = '$softid'");
            if (!$trialret) {
                $this->returnJson('-1', '验证不通过!');
            }

            // 时间差
            $cle = time() - strtotime($trialret[0]['last_time']);
            $m = $cle / 60;

            // 如果大于软件最大使用时间
            if ($m > $softret[0]['try_minutes']) {
                $this->returnJson('-1', '验证不通过,试用到期!');
            } else {
                // token是否改变
                if ($trialret[0]['token'] != $this->_jsonArr->k) {
                    $this->returnJson('-1', '验证不通过,禁止多开!');
                } else {
                    $this->returnJson('0', '验证通过');
                }
            }
        }
    }

    private function trial() {
        // 强制更新检查
        $softret = $this->isSoftwareFrozen();
        if ($this->_jsonArr->v != '' && $this->_jsonArr->v < $softret[0]['version'] && $softret[0]['updatemode'] == '1') {
            $_SESSION['lastCheckRet'] = '-2';
            $this->_param['error'] = $_SESSION['lastCheckRet'];
            $this->_param['msg'] = '请更新最新版本' . $softret[0]['version'];
            $this->_param['url'] = $softret[0]['update_url'];
            $this->_param['t'] = $this->_jsonArr->t;
            $this->secretReturn($this->_param);
        }

        $computer_uid = $this->_jsonArr->ci;
        $softid = $this->_jsonArr->si;
        $last_ip = $this->_jsonArr->ip;
        $last_time = date('Y-m-d H:i:s', time());

        $mysql = M('Software');
        $ret = $mysql->query("SELECT * FROM cloud_software WHERE id = $softid");
        // 软件没找到
        if (!$ret) {
            $this->returnJson('-1', '试用失败,软件没找到!');
        }
        $try_minutes = $ret[0]['try_minutes'];
        $try_count = $ret[0]['try_count'];
        if ($try_minutes <= 0 || $try_count <= 0) {
            $this->returnJson('-1', '试用失败,软件不支持试用!');
        }

        $token = substr(md5($this->_jsonArr->c . time()), 0, 5);

        $trialsql = M('Trial');
        $trialret = $trialsql->query("SELECT * FROM cloud_trial WHERE computer_uid = '$computer_uid' AND software_id = '$softid'");
        // 机器码没找到,第一次试用
        if (!$trialret) {
            $update_ret = $trialsql->execute("INSERT INTO cloud_trial (computer_uid, software_id, last_ip, last_time, token) VALUES ('{$computer_uid}', '{$softid}', '{$last_ip}', '{$last_time}', '{$token}')");

            if ($update_ret) {
                $_SESSION['checkFormalOrTrial'] = 'trial';
                $_SESSION['lastCheckRet'] = '0';
                $this->_param['error'] = '0';
                $this->_param['msg'] = '[登录结果]: 试用成功!还可以试用' . ($ret[0]['try_count'] - 1) . '次\n[软件公告]:' . $softret[0]['info'] . '\n[软件版本]:有新版本' .$softret[0]['version']. ',请更新!';
                $this->_param['token'] = $token;
                $this->_param['t'] = $this->_jsonArr->t;
                $this->secretReturn($this->_param);
            }
            exit();
        }

        // 计算还可以试用的次数
        $remainder_count = $ret[0]['try_count'] - $trialret[0]['has_try_count'];
        if ($remainder_count > 0) {
            $has_try_count = $trialret[0]['has_try_count'] + 1;
            $update_ret = $trialsql->execute("UPDATE cloud_trial SET has_try_count = '$has_try_count', last_ip = '$last_ip', last_time = '$last_time', token = '$token' WHERE computer_uid = '$computer_uid' AND software_id = '$softid'");
            if ($update_ret) {
                $_SESSION['checkFormalOrTrial'] = 'trial';
                $_SESSION['lastCheckRet'] = '0';
                $this->_param['error'] = '0';
                $this->_param['msg'] = '[登录结果]: 试用成功!还可以试用' . ($remainder_count - 1) . '次\n[软件公告]:' . $softret[0]['info'] . '\n[软件版本]:有新版本' .$softret[0]['version']. ',请更新!';
                $this->_param['token'] = $token;
                $this->_param['t'] = $this->_jsonArr->t;
                $this->secretReturn($this->_param);
            }
        } else {
            $this->returnJson('-1', '试用失败,试用次数已用完!');
        }

    }

    // 下线
    private function offline() {
        $mysql = M('Regcode');
        $code = $this->_jsonArr->c;
        $mysql->execute("UPDATE cloud_regcode SET isonline = 0 WHERE code = '{$code}'");
    }

    private function isComputerIdValid() {
        if ($this->_jsonArr->ci == '') {
            $this->returnJson('300', '验证不通过,机器码为空!');
        }
    }

    // 软件是否禁用
    private function isSoftwareFrozen() {
        // 软件是否禁用
        $softid = $this->_jsonArr->si;
        $mysql = M('Software');
        $ret = $mysql->query("SELECT * FROM cloud_software WHERE id = $softid");
        // 软件没找到
        if (!$ret) {
            $this->offline();
            $this->returnJson('200', '验证不通过,没有该软件!');
        }
        if ($ret[0]['frozen'] == '1') {
            $this->offline();
            $this->returnJson('201', '验证不通过,软件被禁用!');
        }
        return $ret;
    }

    // 注册码是否可用
    private function isCodeValid() {
        // 注册码是否空
        $code = $this->_jsonArr->c;
        if ($code == '') {
            $this->returnJson('101', '注册码不能为空!');
        }

        $mysql = M('Regcode');
        $ret = $mysql->query("SELECT * FROM cloud_regcode WHERE code = '$code'");
        // 注册码没找到
        if (!$ret) {
            $this->returnJson('102', '注册码不存在!');
        }
        // 注册码过期
        if ($ret[0]['overdue'] == 1) {
            $this->offline();
            $this->returnJson('103', '注册码已过期!');
        }
        if ($ret[0]['expire_time']) {
            if (strtotime(date('Y-m-d H:i:s', time())) - strtotime($ret[0]['expire_time']) > 0) {
                $mysql->execute("UPDATE cloud_regcode SET overdue = 1 WHERE code = '$code'");
                $this->offline();
                $this->returnJson('103', '注册码已过期!');
            }
        }
        // 注册码冻结
        if ($ret[0]['frozen'] == 1) {
            $this->offline();
            $this->returnJson('104', '注册码被冻结!');
        }

        return $ret;
    }

    // 保存上次的error编号并返回json
    private function returnJson($error, $msg) {
        $_SESSION['lastCheckRet'] = $error;
        $this->_param['error'] = $error;
        $this->_param['msg'] = $msg;
        $this->secretReturn($this->_param);
        exit();
    }

    // des加密后返回
    private function secretReturn($data){
        // 客户端请求时的时间戳,返回回去,用于判断是不是伪造的返回结果防破解
        $data['t'] = $this->_jsonArr->t;
        $this->logReturnJson(json_encode($data));

        header('Content-Type:application/json; charset=utf-8');
        $des = \DES::encrypt("#h2@j7!a", json_encode($data));
        $out = base64_encode($des);
        exit($out);
    }

    private function logReturnJson($a) {
        $content = "## 来访时间：" . date('Y-m-d H:i:s') . " IP：" . $_SERVER["REMOTE_ADDR"] . "\n## 返回信息：" . $a . "\n";

        if (isset($_SERVER['HTTP_APPNAME'])) {    // SAE
            sae_set_display_errors(false);
            sae_debug(trim($content));
            sae_set_display_errors(true);
        } else {
            $today = date('Ymd');
            $log_filename = 'file/' . $today . '.log';
            file_put_contents($log_filename, $content, FILE_APPEND);
        }
    }

    // 定期任务 当天没有日志没出现过的注册码 进行踢下线处理
    private function searchIsOnline() {
        $today = date('Ymd');
        $log_filename = 'file/' . $today . '_offline.log';
        if (file_exists($log_filename)) {
            return;
        }
        $mysql = M();
        $ret = $mysql->query("SELECT *FROM cloud_regcode WHERE isonline = 1");

        // 昨天的日志
        $yestoday = date('Ymd');
        $request_log_file = 'file/' . $yestoday . '.log';
        $txt = file_get_contents($request_log_file);

        foreach ($ret as $dict) {
            if (substr_count($txt, $dict['code']) == 0) {
                $code = $dict['code'];
                $mysql->execute("UPDATE cloud_regcode SET isonline = 0 WHERE code = '$code'");
                file_put_contents($log_filename, $code . "\n", FILE_APPEND);
            }
        }

        if (!file_exists($log_filename)) {
            file_put_contents($log_filename, '今日没有需要强制下线的注册码' , FILE_APPEND);
        }
    }
}