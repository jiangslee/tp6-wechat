<?php
/**
 * ThinkPHP微信缓存类
 *
 * @author yzh52521<396751927@qq.com>
 * @copyright yzh52521
 */

namespace jiangslee\ThinkWechat;

use Psr\SimpleCache\CacheInterface;
use think\Cache;

class CacheBridge implements CacheInterface
{
    protected ?Cache $cache = null;

    public function __construct()
    {
        $this->cache = app('cache');
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->cache->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->cache->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->cache->deleteMultiple($keys);
    }

    public function has($key)
    {
        return $this->cache->has($key);
        // return !is_null($this->cache->get($key));
    }
}
