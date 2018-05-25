<?php
/**
 * Created by PhpStorm.
 * User: hezhizheng
 * Email: 920625788@qq.com
 * Date: 2018/5/23 0023
 * Time: 下午 21:44
 */

namespace app;

use GuzzleHttp\Client;
use QL\QueryList;

require '../vendor/autoload.php';
require_once 'config.php';

class Run extends Config
{
    protected $queryList;
    protected $client;

    public function __construct()
    {
        $this->queryList = new QueryList();
        $this->client = new Client();
    }

    public function test_post()
    {

        $verify = $this->client->request('POST', Config::VERIFY_URL, [
            'body' => json_encode([
//                'account' => 'admin',
//                'password' => 'fghjkl',
//                'type' => 'customer',
                'username' => Config::USERNAME,
                'password' => md5(Config::PASSWORD),
            ]),
            'headers' => [
                'User-Agent'   => 'okhttp/3.4.1',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        return $verify;
    }

    public function getCookie()
    {
        // 验证是否要输入验证码
        try {
            $verify = $this->queryList->post(
                Config::VERIFY_URL,
                [
                    'username' => Config::USERNAME,
                    'password' => md5(Config::PASSWORD),
                ],
                [
                    'headers' => [
                        'User-Agent'   => 'okhttp/3.4.1',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]
                ]
            )->gethtml();
        } catch (\Exception $exception) {
            $this->save_log('verify_error');
            $this->save_log($exception->getCode() . $exception->getMessage());
            return $exception->getCode() . $exception->getMessage();
        }

        return $verify;
//        var_dump($verify);
//        die($verify);

        // todo 处理验证码

        // login 获取cookie


    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCookie444555()
    {
        // 验证是否要输入验证码
        try {
            $verify = $this->client->request('POST', Config::VERIFY_URL, [
                'form_params' => [
                    'username' => Config::USERNAME,
                    'password' => md5(Config::PASSWORD),
                ],
                'headers'     => [
                    'User-Agent'   => 'okhttp/3.4.1',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);
        } catch (\Exception $exception) {
            $this->save_log('verify_error');
            $this->save_log($exception->getCode() . $exception->getMessage());
            return $exception->getCode() . $exception->getMessage();
        }

        return $verify;
//        var_dump($verify);
//        die($verify);

        // todo 处理验证码

        // login 获取cookie


    }


    public function actionSignIn()
    {

        try {
            $ql = new QueryList();
            $ql->post(
                $this->getSignInUrl(),
                $this->getPublicParam(),
                [
                    'headers' => [
                        'Cookie'       => Config::COOKIE,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]
                ]);
            $html = $ql->getHtml();
            $this->save_log($html); // 保存日志
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('SignIn_error');
            $this->save_log($exception->getCode() . $exception->getMessage());
            return $exception->getCode() . $exception->getMessage();
        };

    }

    public function actionAd()
    {

        try {
            $html = $this->postAd();
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('ad_error');
            $this->save_log($exception->getCode() . $exception->getMessage());
            return $exception->getCode() . $exception->getMessage();
        };

    }

    private function postAd()
    {
        $ql = new QueryList();
        $post = $ql->post(
            $this->getAdUrl(),
            $this->getPublicParam(),
            [
                'headers' => [
                    'Cookie'       => Config::COOKIE,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ])->getHtml();
        $this->save_log($post);// 保存日志
        if (json_decode($post, true)['todayCount'] < 3) { // 主动看三次广告
            $post = $this->actionAd();
        }

        return $post;
    }


    public function actionAll()
    {
        $actionSignIn = $this->actionSignIn();

        $ad = $this->actionAd();

        if (json_decode($actionSignIn, true)['success'] == 1) {
            $SignIn_info = '恭喜你，签到获取MB成功！';

        } else {
            $SignIn_info = '签到失败，你今天可能已经签到过了！';

        }

        if (json_decode($ad, true)['success'] == true) {
            $ad_info = '恭喜你，看广告获取MB成功！' . '此次为你主动看了 ' . json_decode($ad, true)['todayCount'] . ' 次广告';

        } else {
            $ad_info = '看广告获取MB失败！你可能已经看过超过三次，目前已观看 ' . json_decode($ad, true)['todayCount'] . ' 次';

        }

        return [
            'sing_in' => $SignIn_info,
            'ad_info' => $ad_info,
        ];
    }

}

$run = new Run();

var_dump( $run->test_post()->getHeaders());
//var_dump($run->getCookie());
die();

$action = $run->actionAll();

$sing_in_info = '签到状态：' . $action['sing_in'];
$ad_info = "\n" . '看广告状态：' . $action['ad_info'] . "\n";

print($sing_in_info);
print($ad_info);

$run->save_log([
    $sing_in_info,
    $ad_info,
]);