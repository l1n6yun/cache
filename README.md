# Cache

一个简单的文件缓存类

## 安装

```shell
# 首次安装 线上版本（稳定）
composer require l1n6yun/cache

# 首次安装 开发版本（开发）
composer require l1n6yun/cache:dev-master

# 更新
composer update l1n6yun/cache
```

## 使用

```php
<?php

require './vendor/autoload.php';

use L1n6yun\Cache\Cache;

$cache = new Cache();

$data = [
    'id'   => 1,
    'name' => 'l1n6yun',
    'age'  => 18,
    'sex'  => 'man',
];

$cache->setCache('user_1', $data, 7200);

$user = $cache->getCache('user_1', []);

var_export($user);
```

输入

```php
array (
  'id' => 1,
  'name' => 'l1n6yun',
  'age' => 18,
  'sex' => 'man',
)
```

缓存文件默认情况下保存在 `/tmp/cache` 目录中

```shell
$ tree /tmp/cache
/tmp/cache
  └─ user_1
```

清楚缓存

```php
$user->delCache('user_1');
```

修改缓存路径

```php
$options = ['cache_path' => __DIR__.'/cache'];

$cache = new Cache($options);

$cache->setCache('key', 'value');
```

```shell
$ tree ./cache
./cache
  └─ user_1
```