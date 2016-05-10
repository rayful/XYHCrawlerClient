<?php

/**
 * Created by PhpStorm.
 * User: kingmax
 * Date: 16/5/9
 * Time: 下午9:23
 */
class Test extends PHPUnit_Framework_TestCase
{
    private $key = "testKey";

    private $serverUrl = "http://127.0.0.1/~kingmax/XYHCrawler/";

    public function setUp()
    {
        require_once __DIR__."/../src/Client.php";
    }

    public function testClient()
    {
        $urls = [
            "http://www.mytheresa.com/int_en/wool-crepe-coat-with-optional-shearling-vest-545469.html?catref=category",
            "http://www.mytheresa.com/int_en/rockstud-leather-cross-body-bag-549487.html?catref=category",
            "https://www.net-a-porter.com/gb/zh/product/681462/dolce___gabbana/--------------",
            "http://www.luisaviaroma.com/mykita/%E7%94%B7%E5%A3%AB/%E5%A4%AA%E9%98%B3%E9%95%9C/63I-K28006/lang_ZH/colorid_NjAz0?SubLine=accessories&CategoryId=54",
        ];
        $Client = new \XYHCrawlerClient\Client();
        $Client->setUrls($urls);
        $Client->setPageMax(2);
        $Client->setDoSubs(true);
        $Result = $Client->execute($this->key, $this->serverUrl);
        print_r($Result);
    }
}
