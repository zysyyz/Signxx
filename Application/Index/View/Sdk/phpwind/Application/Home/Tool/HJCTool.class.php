<?php
namespace Home\Tool;

class HJCTool {
    static public function alertBack($msg) {
        echo "<meta charset='utf-8'><script>alert('" . $msg . "');history.back();</script>";
        exit();
    }

    static public function alert($msg) {
        echo "<meta charset='utf-8'><script>alert('" . $msg . "');</script>";
        exit();
    }

    static public function alertToLocation($msg, $url) {
        if (!$msg) {
            echo "<meta charset='utf-8'><script>location.href='" . $url . "';</script>";
        } else {
            echo "<meta charset='utf-8'><script>alert('" . $msg . "');location.href='" . $url . "';</script>";
        }
        exit();
    }

    static public function getRealIP() {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

    // 加密密码
    static public function secret($msg) {
        $md5 = md5($msg);
        $newmd5 = $md5[4] . $md5[3] . $md5[0] . $md5[1] . $md5[2] . substr($md5, '5', strlen($md5));
        return sha1($newmd5);
    }

    /*
 * $text是输入的文本；
 * $word是原来的字符串；
 * $cword是需要替换成为的字符串；
 * $pos是指$word在$text中第N次出现的位置，从1开始算起
 * */
    static function strReplaceOnce($text, $word, $cword, $pos = 1) {
        $text_array = explode($word, $text);
        $num = count($text_array) - 1;
        if ($pos > $num) {
            return "the number is too big!or can not find the $word";
        }
        $result_str = '';
        for ($i = 0; $i <= $num; $i++) {
            if ($i == $pos - 1) {
                $result_str .= $text_array[$i] . $cword;
            } else {
                $result_str .= $text_array[$i] . $word;
            }
        }
        return rtrim($result_str, $word);
    }
}

