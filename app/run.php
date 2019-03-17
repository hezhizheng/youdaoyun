<?php
/**
 * Created by PhpStorm.
 * User: hezhizheng
 * Email: 920625788@qq.com
 * Date: 2018/5/23 0023
 * Time: 下午 21:44
 */

namespace app;

use QL\QueryList;

require __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

class Run extends Config
{
    protected $queryList;

    public $log_path;

    public function __construct()
    {
        $this->queryList = new QueryList();
        $this->log_path = __DIR__ . '/../error';
    }

    public function actionLog()
    {
        try {
            $ql = new QueryList();
            $ql->post(
                $this->getLogUrl(),
                $this->getPublicParam(),
                [
                    'headers' => [
                        'Cookie' => Config::COOKIE,
                        'Content-Type' => 'application/json',
                    ]
                ]);
//            var_dump($ql);exit();
            $html = $ql->getHtml();
            $this->save_log($html, $this->log_path); // 保存日志
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('LoginIn_error', $this->log_path);
            $this->save_log($exception->getCode() . $exception->getMessage(), $this->log_path);
            return $exception->getCode() . $exception->getMessage();
        };
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
                        'Cookie' => Config::COOKIE,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]
                ]);
            $html = $ql->getHtml();
            $this->save_log($html, $this->log_path); // 保存日志
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('SignIn_error', $this->log_path);
            $this->save_log($exception->getCode() . $exception->getMessage(), $this->log_path);
            return $exception->getCode() . $exception->getMessage();
        };

    }

    public function actionAd()
    {

        try {
            $html = $this->postAd();
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('ad_error', $this->log_path);
            $this->save_log($exception->getCode() . $exception->getMessage(), $this->log_path);
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
                    'Cookie' => Config::COOKIE,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ])->getHtml();
        $this->save_log($post, $this->log_path);// 保存日志
        if (json_decode($post, true)['todayCount'] < 3) { // 主动看三次广告
            $post = $this->actionAd();
        }

        return $post;
    }

    public function actionVideoAd()
    {

        try {
            $html = $this->postVideoAd();
            return $html;
        } catch (\Exception $exception) {
            $this->save_log('video_ad_error', $this->log_path);
            $this->save_log($exception->getCode() . $exception->getMessage(), $this->log_path);
            return $exception->getCode() . $exception->getMessage();
        };

    }

    private function postVideoAd()
    {
        $ql = new QueryList();
        $post = $ql->post(
            $this->getVideoAdUrl(),
            $this->getPublicParam(),
            [
                'headers' => [
                    'Cookie' => Config::COOKIE,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ])->getHtml();
        $this->save_log($post, $this->log_path);// 保存日志
        if (json_decode($post, true)['todayCount'] < 3) { // 主动看三次广告
            $post = $this->postVideoAd();
        }

        return $post;
    }

    public function actionAll()
    {

        $log = $this->actionLog();

        $actionSignIn = $this->actionSignIn();

        $ad = $this->actionAd();

        $videoAd = $this->actionVideoAd();

        $log_info = '登录状态未知。';
        if ($log=='') {
            $log_info = '登录成功！'; // 带验证！！！
        }

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

        if (json_decode($videoAd, true)['success'] == true) {
            $video_ad_info = '恭喜你，看视频广告获取MB成功！' . '此次为你主动看了 ' . json_decode($videoAd, true)['todayCount'] . ' 次广告';

        } else {
            $video_ad_info = '看视频广告获取MB失败！你可能已经看过超过三次，目前已观看 ' . json_decode($videoAd, true)['todayCount'] . ' 次';

        }

        return [
            'log_info' => $log_info,
            'sing_in' => $SignIn_info,
            'ad_info' => $ad_info,
            'video_ad_info' => $video_ad_info
        ];
    }

}

$run = new Run();

//var_dump($run->test_post());
//var_dump($run->getCookie());
//die();

$action = $run->actionAll();

$login_info = '登录状态：' . $action['log_info']. "\n";
$sing_in_info = '签到状态：' . $action['sing_in'];
$ad_info = "\n" . '看广告状态：' . $action['ad_info'] . "\n";
$video_ad_info = '看视频广告状态：' . $action['video_ad_info'] . "\n";

print($login_info);
print($sing_in_info);
print($ad_info);
print($video_ad_info);

$run->save_log([
    $login_info,
    $sing_in_info,
    $ad_info,
    $video_ad_info
], $run->log_path);
