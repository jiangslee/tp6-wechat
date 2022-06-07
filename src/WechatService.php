<?php
namespace jiangslee\ThinkWechat;

use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\OpenWork\Application as OpenWork;
use EasyWeChat\Pay\Application as Payment;
use EasyWeChat\Work\Application as Work;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use think\Service;

class WechatService extends Service
{

    /*
     *  usage:
     *      app( $module_name)
     *  or
     *      app( $module_name, [ app_id => 'test' ])
     */
    public function boot()
    {
        $apps = [
            'official_account' => OfficialAccount::class,
            'work'             => Work::class,
            'mini_app'         => MiniApp::class,
            'payment'          => Payment::class,
            'open_platform'    => OpenPlatform::class,
            'open_work'        => OpenWork::class,
        ];
        $wechat_default = config('wechat.default') ? config('wechat.default') : [];
        foreach ($apps as $name => $class) {
            if (!config('wechat.' . $name)) {
                continue;
            }
            $accounts = config('wechat.' . $name);
            foreach ($accounts as $account => $config) {
                
                $this->app->bind("wechat.{$name}.{$account}", function ($close_config = []) use ($class, $config, $wechat_default) {
                    //合并配置文件
                    $class_config = array_merge($config, $wechat_default, $close_config);
                    
                    if (config('wechat.inject_think_logger')) {
                        $class_config['log']['default'] = 'thinkphp';
                    }
                    $app = new $class($class_config);
                    
                    if (config('wechat.default.use_tp_cache')) {
                        if (\is_callable([$app, 'setCache'])) {
                            $app->setCache(app(CacheBridge::class));
                        }
                    }
                    

                    // if (\is_callable([$app, 'setRequestFromSymfonyRequest'])) {
                    //     $app->setRequestFromSymfonyRequest(app(HttpFoundationRequest::class));
                    // }

                    return $app;
                });
            }
            if (isset($accounts['default'])) {
                $this->app->bind('wechat.' . $name, 'wechat.' . $name . '.default');
            }
        }
    }
}
