<?php
/**
 * oauth登录中间件
 *
 * @author yzh52521<396751927@qq.com>
 * @copyright  yzh52521
 */

namespace jiangslee\ThinkWechat\Middleware;

use EasyWeChat\OfficialAccount\Application;
use think\facade\Log;
use think\Request;
use think\facade\Session;

class OauthMiddleware
{
    /**
     * 执行中间件
     *
     * @param Request  $request
     * @param \Closure $next
     * @param null     $param
     * @return mixed|\think\response\Redirect
     */
    public function handle(Request $request, \Closure $next, $param = null)
    {
        $params = $this->getParam($param);
        $account = $params["account"];
        $scopes = $params["scopes"] ?: config(sprintf('wechat.official_account.%s.oauth.scopes', $account), ['snsapi_base']);

        if (\is_string($scopes)) {
            $scopes = \array_map('trim', explode(',', $scopes));
        }

        //定义session
        $session_key = 'wechat_oauth_user_' . $account;
        $session = Session::get($session_key);
        Log::info(sprintf('%s:%s', __FILE__, __LINE__), ['session' => $session]);
        if (!$session) {
            /** @var Application $officialAccount */
            $officialAccount = app(sprintf('wechat.official_account.%s', $account));

            /** @var \Overtrue\Socialite\Contracts\ProviderInterface|\Overtrue\Socialite\Providers\Wechat */
            $oauth = $officialAccount->getOAuth();

            if ($request->get('code')) {
                $session = $oauth->userFromCode($request->query('code'));
                Session::set([$session_key => $session]);
                //跳转到登录
                Log::info(sprintf('%s:%s', __FILE__, __LINE__), ['targetUrl' => $this->getTargetUrl($request)]);
                return redirect($this->getTargetUrl($request));
            }

            $url = $oauth->scopes($scopes)->redirect($request->url(true))->getTargetUrl();
            Log::info(sprintf('%s:%s', __FILE__, __LINE__), ['redirectUrl' => $url]);
            return redirect($url);
        }
        return $next($request);
    }


    /**
     * @param $params
     * @return array
     */
    protected function getParam($params)
    {
        //定义初始化
        $res["account"] = "default";
        $res['scopes'] = null;
        if (!$params) {
            return $res;
        }
        //解析
        $result = explode(":", $params);
        $account = "";
        $scopes = "";
        if (isset($result[0])) {
            $account = $result[0];
        }
        if (isset($result[1])) {
            $scopes = $result[1];
        }
        if ($account) {
            if (strpos($account, "sns") !== false) {
                $res["scopes"] = $account;
            } else {
                $res["account"] = $account;
            }
        }
        if ($scopes) {
            $res["scopes"] = $scopes;
        }
        return $res;
    }

    /**
     * Build the target business url.
     *
     * @param Request $request
     *¬
     * @return string
     */
    protected function getTargetUrl($request)
    {
        $param = $request->get();
        if (isset($param['code'])) {
            unset($param['code']);
        }
        if (isset($param['state'])) {
            unset($param['state']);
        }
        return $request->baseUrl() . (empty($param) ? '' : '?' . http_build_query($param));
    }
}
