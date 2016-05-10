<?php
/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/5/8
 * Time: 上午8:18
 */

namespace XYHCrawlerClient;


class Client
{
    /**
     * 请求的URL
     * @var string
     */
    private $url;

    /**
     * 请求的URL(多个)
     * @var array
     */
    private $urls;

    /**
     * 订阅模式，这里指定一个回调URL
     * @var string
     */
    private $subscribed_callback;

    /**
     * 缓存模式，产品模式默认为null,调试模式默认为redis,可选值:file/redis
     * @var string|null
     */
    private $cache;

    /**
     * 最大页数.产品模式默认为1,调试模式默认为10.
     * @var int
     */
    private $page_max;

    /**
     * 是否采子列表
     * @var boolean
     */
    private $do_subs;

    /**
     * 签名(见validateSign方法)
     * @var string
     */
    private $sign;

    /**
     * cache模式的可选值
     */
    const CACHE_FILE = "file";

    /**
     * cache模式的可选值
     */
    const CACHE_REDIS = "redis";

    /**
     * (二选一必选)设定请求单个URL地址
     * @param string $url
     * @return Client
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * (二选一必选)同时设定请求的多个URL地址
     * @param array $urls
     * @return Client
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
        return $this;
    }

    /**
     * (可选)如果有设定这个callback地址,系统将变成订阅式的异步请求.会待执行处理完成后,将结果以post的形式通知客户端.
     * @param string $subscribed_callback_url
     * @return Client
     */
    public function setSubscribedCallback($subscribed_callback_url)
    {
        $this->subscribed_callback = $subscribed_callback_url;
        return $this;
    }

    /**
     * (可选)缓存模式,默认不开启,可选值:file/redis
     * @param null|string $cache
     * @return Client
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * (可选)最大页数,如果不设定，默认为0.
     * @param int $page_max
     * @return Client
     */
    public function setPageMax($page_max)
    {
        $this->page_max = $page_max;
        return $this;
    }

    /**
     * (可选)是否采子列表,如果不设定，默认为不采.
     * @param boolean $do_subs
     * @return Client
     */
    public function setDoSubs($do_subs)
    {
        $this->do_subs = $do_subs;
        return $this;
    }

    public function execute($key, $serverUrl)
    {
        $this->validate();
        $this->genSign($key);
        $requestUrl = $this->genUrl($serverUrl);
        $result = $this->requestByCurl($requestUrl);
        return json_decode($result);
    }

    private function genSign($key)
    {
        $this->sign = md5($this->url . implode(",", (array)$this->urls) . $key);
    }

    private function validate()
    {
        if (!$this->url && !$this->urls) {
            throw new \Exception("必须至少设定一个URL地址.");
        }

        if ($this->url && $this->urls) {
            throw new \Exception("你不能同时设定url与urls参数.");
        }

        if ($this->subscribed_callback && !filter_var($this->subscribed_callback, FILTER_VALIDATE_URL)) {
            throw new \Exception("subscribed_callback必须是一个合法的URL地址.");
        }

        if ($this->page_max && !is_numeric($this->page_max)) {
            throw new \Exception("最大页数必须为数字.");
        }

        if ($this->cache && $this->cache != self::CACHE_FILE && $this->cache != self::CACHE_REDIS) {
            throw new \Exception("cache模式不是正确的可选值.");
        }
    }

    private function genUrl($serverUrl)
    {
        $query = http_build_query($this);
        return $serverUrl . "?" . $query;
    }

    private function requestByCurl($requestUrl)
    {
        $ch = curl_init($requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}