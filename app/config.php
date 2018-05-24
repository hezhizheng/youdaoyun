<?php
/**
 * Created by PhpStorm.
 * User: hezhizheng
 * Email: 920625788@qq.com
 * Date: 2018/5/23 0023
 * Time: 下午 21:46
 */

namespace app;

class Config{

    const USERNAME = 'hezhizheng@126.com';
    const PASSWORD = '4212671';

    // 检查是否要输入验证码
    const VERIFY_URL = 'https://note.youdao.com/login/acc/urs/verify/check?app=android&product=YNOTE&tp=urstoken&cf=7&show=true&systemName=android&systemVersion=24&resolution=2040*1080&pdtVersion=6.2.3&mac=003a383054459c141e8c2495ee7dc6c0&deviceType=android&os=android&os_ver=24&device_name=BND-AL10&device_model=BND-AL10&device_id=android-8bbbac7d-d5c9-469a-bf0a-f3bcf9213f3e-1522255716140&client_ver=6.2.3&device_type=android';

    const LOGIN_URL = 'https://note.youdao.com/login/acc/login?app=android&product=YNOTE&tp=urstoken&cf=7&show=true&vcode=zb78u&systemName=android&systemVersion=24&resolution=2040*1080&pdtVersion=6.2.3&mac=003a383054459c141e8c2495ee7dc6c0&deviceType=android&os=android&os_ver=24&device_name=BND-AL10&device_model=BND-AL10&device_id=android-8bbbac7d-d5c9-469a-bf0a-f3bcf9213f3e-1522255716140&client_ver=6.2.3&device_type=android';

    const SIGN_IN_URL = 'https://note.youdao.com/yws/mapi/user?method=checkin'; // 签到获取mb url

    const AD_URL = 'https://note.youdao.com/yws/mapi/user?method=adPrompt'; // 看广告获取mb url

    const COOKIE = 'YNOTE_LOGIN=true; YNOTE_SESS=v2|rtpifj0dlWOm0Mpy0LQB0wS0fzGhMUM0QFRHgLnLTz0k5hfPuOfYM0qLOLQ40LzfR6F6LeuRfYf0OEkMTuPMJ4Rg46LPL64ez0';

    const PUBLIC_PARAM = [
        'imei' => '003a383054459c141e8c2495ee7dc6c0',
        'mid' => 'REL 7.0',
        'level' => 'user',
        'location' => 'memory',
        'net' => 'wifi',
        'apn' => 'wifi',
        'username' => 'hezhizheng@126.com',
        'login' => 'netease',
        'phoneVersion' => 'android',
        'model' => 'BND - AL10',
        'vendor' => 'huawei',
        'first_vendor' => 'yingyongbao',
        'keyfrom' => 'note.6.2.3 . android',
        'dpi' => '480',
        'resolution' => '1080 * 2040',
        'bg' => '0',
        'size' => '2.25 * 4.25',
        'os_arch' => 'armv7l',
        'cpu_abi' => 'armeabi - v7a',
        'os' => 'android',
        'os_ver' => '24',
        'device_name' => 'HWBND - H',
        'device_model' => 'BND - AL10',
        'device_id' => 'android - 8bbbac7d - d5c9 - 469a - bf0a - f3bcf9213f3e - 1522255716140',
        'client_ver' => '6.2.3',
        'device_type' => 'android',
    ];

    public function getPublicParam()
    {
        return self::PUBLIC_PARAM; //eval()函数把字符串作为PHP代码执行
    }

    public function getSignInUrl()
    {
        return self::SIGN_IN_URL;
    }

    public function getAdUrl()
    {
        return self::AD_URL;
    }

    /**
     * 记录错误日志
     * @param $res
     */
    public function save_log($res) {
        $err_date = date("Ymd", time());
        //$address = '/var/log/error';
        $address = './error';
        if (!is_dir($address)) {
            mkdir($address, 0700, true);
        }
        $address = $address.'/'.$err_date . '_error.log';
        $error_date = date("Y-m-d H:i:s", time());
        if(!empty($_SERVER['HTTP_REFERER'])) {
            $file = @$_SERVER['HTTP_REFERER'];
        } else {
            $file = @$_SERVER['REQUEST_URI'];
        }
        if(is_array($res)) {
            $res_real = "$error_date\t$file\n";
            error_log($res_real, 3, $address);
            $res = var_export($res,true);
            $res = $res."\n";
            error_log($res, 3, $address);
        } else {
            $res_real = "$error_date\t$file\t$res\n";
            error_log($res_real, 3, $address);
        }
    }

}