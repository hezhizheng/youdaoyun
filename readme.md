# 有道云APP(Android版)签到、看广告获取容量(空间)

> 注：此功能对会员可能不适用

> 说明：并非全自动，需要使用fiddler或其他抓包工具获取到COOKIE 、当前请求所携带的参数PUBLIC_PARAM。没有IOS手机，但感觉应该差不多，有兴趣的朋友可以自己试试。

## 安装
```
git clone 本项目到本地 

composer update -vvv

```

## 获取有道云参数
```
打开fiddler与有道云APP 点击签到按钮 获取请求的 COOKIE 与 参数，

将获取到的值替换config.php文件对应的COOKIE与PUBLIC_PARAM常量值即可。
```
## 使用
```
控制台中输入 php app/run.php
如无意外你会看见如下输出
```

![image](https://raw.githubusercontent.com/hezhizheng/youdaoyun/master/echo.png)


## 每天定时执行签到
Liunx 系统下

```
用命令crontab -e 添加如下内容

0 0 * * * /usr/bin/php youdao/app/run.php  // 填绝对路径！！！
```

> 关于cookie的有效期：暂时不确定有效期是多久，目前的cookie已经用了好几天了，并没有失效。


