# 西洋汇爬虫系统客户端

定义全局变量
```php
$key = "testKey";
$serverUrl = "http://127.0.0.1/CrawlerServer/";
```

采集产品页:
```php
$Client = new \XYHCrawlerClient\Client();
$Client->setUrl($url);
$Result = $Client->execute($key, $serverUrl);
print_r($Result);
```

采集列表页:
```php
$Client = new \XYHCrawlerClient\Client();
$Client->setUrl($url);
$Client->setPageMax(2);
$Client->setDoSubs(true);

$Result = $Client->execute($key, $serverUrl);
print_r($Result);
```

采集多地址
```php
$urls = [
    "http://www.mytheresa.com/int_en/wool-crepe-coat-with-optional-shearling-vest-545469.html?catref=category",
    "http://www.mytheresa.com/int_en/rockstud-leather-cross-body-bag-549487.html?catref=category",
    "https://www.net-a-porter.com/gb/zh/product/681462/dolce___gabbana/--------------",
    "http://www.luisaviaroma.com/mykita/%E7%94%B7%E5%A3%AB/%E5%A4%AA%E9%98%B3%E9%95%9C/63I-K28006/lang_ZH/colorid_NjAz0?SubLine=accessories&CategoryId=54",
];
$Client = new \XYHCrawlerClient\Client();
$Client->setUrls($urls);
$Result = $Client->execute($key, $serverUrl);
print_r($Result);
```