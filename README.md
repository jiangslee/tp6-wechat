# think-wechat

微信SDK For  ThinkPHP 6.0 基于[overtrue/wechat](https://github.com/overtrue/wechat)

## 框架要求

ThinkPHP >= 6.0

## 安装

```bash
composer require jiangslee/think-wechat:dev-ew6 -vvv
```

## 配置

1. 修改配置文件
修改项目根目录下config/wechat.php中对应的参数

2. 每个模块基本都支持多账号，默认为 default。

3. .env 文件
```env
[WECHAT]
# 公众号
WECHAT_OFFICIAL_ACCOUNT_APPID=
WECHAT_OFFICIAL_ACCOUNT_SECRET=
WECHAT_OFFICIAL_ACCOUNT_TOKEN=
WECHAT_OFFICIAL_ACCOUNT_AES_KEY=
WECHAT_OFFICIAL_ACCOUNT_OAUTH_SCOPES=
WECHAT_OFFICIAL_ACCOUNT_OAUTH_CALLBACK=

# 微信支付
WECHAT_PAYMENT_APPID=
WECHAT_PAYMENT_MCH_ID=
WECHAT_PAYMENT_KEY= 
WECHAT_PAYMENT_V2_KEY= 
WECHAT_PAYMENT_CERT_PATH=156xxxxxx1/apiclient_cert.pem
WECHAT_PAYMENT_KEY_PATH=156xxxxxx1/apiclient_key.pem
WECHAT_PAYMENT_NOTIFY_URL=https://api.domain.com/wexin/notify
```
## 使用

> 注意：在微信后台配置回调url时，报错：“token验证失败”，移除一下`think-trace`

```
composer remove topthink/think-trace --dev
```

### 接受普通消息

```php
<?php

namespace app\index\controller;

use app\BaseController;

class Wechat extends BaseController
{

    public function index()
    {
        // 初始化微信
        $app = app('wechat.official_account');
        $server = $app->getServer();
        
        $server->addMessageListener('text', function ($message) {
            return sprintf("你对 overtrue 说：“%s”", $message->Content);
        });
        // return $server->serve();
        
        $response = $server->serve();
        return $response->getBody();
    }
}
```

### 获得SDK实例

#### 直接用 `app()`

```php
$official_account = app('wechat.official_account');
$mini = app('wechat.mini_app');
$payment = app('wechat.payment');
$work = app('wechat.work');
$open_platform = app('wechat.open_platform');
$open_work = app('wechat.open_work');
```
#### 使用facade

```php
use jiangslee\ThinkWechat\Facade;

$officialAccount = Facade::officialAccount();  // 公众号
$miniApp = Facade::miniApp(); // 小程序
$payment = Facade::payment(); // 微信支付
$openPlatform = Facade::openPlatform(); // 开放平台
$work = Facade::work(); // 企业微信
$openWork = Facade::openWork(); // 企业微信第三方服务商
```

以上均支持传入自定义账号:例如

```php
$officialAccount = Facade::officialAccount('test'); // 公众号
```

以上均支持传入自定义账号+配置(注:这里的config和配置文件中账号的格式相同):例如

```php
$officialAccount = Facade::officialAccount('',$config); // 公众号
```
### Oauth登录中间件(ThinkPHP6.0+)
使用中间件情况下，config的oauth.callback可以随便写~，反正是直接获取了当前URL
```php
\think\facade\Route::rule('user','wechat/user')->middleware(\jiangslee\ThinkWechat\Middleware\OauthMiddleware::class);
```

上面的路由定义了 /user 是需要微信授权的，那么在这条路由的回调 或 控制器对应的方法里， 你就可以从 session('wechat_oauth_user_default') 拿到已经授权的用户信息了。


关于ThinkPHP6.0的中间件使用方法不在叙述，详情可以查看[官方文档](https://www.kancloud.cn/manual/thinkphp6_0/1037493)

#### 中间件参数说明
由于ThinkPHP中间件只支持一个参数，所以以`:`做分割

支持传入account账号别名以及scope类型

若不传入`account`，会使用`default`账号

若不传入`scope`，会使用配置文件中的`oauth.scope`

支持一下两种方式
```
default:snsapi_base
snsapi_base
```


更多 SDK 的具体使用请参考：[https://easywechat.com](https://easywechat.com)

## License

MIT
