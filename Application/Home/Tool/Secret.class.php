<?php
namespace Home\Tool;

class Secret {

    public function privateEncode($encryptData) {
        $privateKeyStr = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDABYhYEQdosREVBl/3nDVHRFEaz6Lvfc+oXl3UxPVoEHWrQQ8u
tyZthMO0dc459vg3X9ix2EA40ZO1RWEPjuhwpOdKgxOTjpjd85v7uluMChEPfxLb
418/GNxVIpyNDNHOl2xgSVYTobmUYtyGMSUthLrYgiN80uZPWaLUbnp1VwIDAQAB
AoGABHKcW93ZHBWt3ZzOMEAQA9yTPFm+3mk9nBrDdaAyRvzKnPLe1Sgs3bCLj+yC
oFkWGsI/lK77ndVM5I/81Y1r2NXSXJc6t3110Ne0ccBcf2+DpUcGFwbZIknqj5w9
XrXwSzF4W+iKueBiQMO9ElIBKxFwib1wXHVPosTK77lsHVECQQDkmH6htxBR1XUx
Gnnp38MSLUaiTPisgEDBUIaS2Q8AMbTkA1Eq/U3Bum791r2Ptq/8UTcXjY1tX/ru
2dD96v1TAkEA1wqYWDPqwRixBRJLYCAT2ACoXrwZBZmofocqKxZN7xy0jgwSrT++
RHxsOeCsHfnyIZtZlA4GuX44r/qB3ITjbQJAdVdWAVsAFJG283q8w/Gpp6X4EQ3j
xGCdXN4iBjVHfvkE+to9Cw01odE6cjCN47yKP8HMvtlZlKBlJcBHiF/cowJBAJJl
HCGv9nlOnfSd58KhE+FRUU5tL3uoiBTbX9HFdXj7SdAKWAyqAJYPPn6IfaRKrJRE
dk9c3Scazuy+1fmSXmECQB1qHAnkBgGyo+4O2XxETqlo8s2L0dcO8FWoiQj0c2/s
P3XJuYVUZ9XFxzLt+ErkISbDrkvM02yGBRytc3+ECgA=
-----END RSA PRIVATE KEY-----
';

        extension_loaded('openssl');

        /**
         * 生成Resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
         */
        $_privateKey = openssl_pkey_get_private($privateKeyStr);

        if (openssl_private_encrypt('asfda141', $decryptData, $_privateKey)) {
            echo base64_encode($decryptData);
//            echo $decryptData;
        } else {

            echo '';
        }
    }

//    public function privateDecode($decryptData) {
//        if (openssl_private_decrypt($encryptData, $decryptData, $_privateKey)) {
//            return $decryptData;
//        } else {
//            return '';
//        }
//    }

    // 生成随机注册码
    public function createRandRegisterCode() {
        $yz = 'abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+,.?';
        $str = '';
        for ($i = 0; $i< 40; $i++) {
            $index = rand(0, strlen($yz) - 1);
            $str = $str . $yz[$index];
        }
        return strtoupper(md5($str));
    }

}