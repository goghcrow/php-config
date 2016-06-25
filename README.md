## Config


### 1. Intro

1. 根据Zan Framework的Config模块功能重写
2. 可根据加载配置代码, 方便IDE提示


### 2. 配置组织结构示例

```
以mysql为例,见项目Conf目录

./test/Conf
├── dev
│   └── store
│       └── mysql.php
├── product
│   └── store
│       └── mysql.php
├── share
│   └── dict.php
└── test
    └── store
        └── mysql.php
```

按照环境组织配置文件, 示例有dev,test,product三个环境,share是共享配置

三个环境的配置文件会共享share下的配置,且优先级高于share下,同名key覆盖

默认特定目录加载*.php作为配置文件,所有配置文件约定返回数组


### 3. 配置示例

```php
return [
    "default" => [
        "port" => 3306,
        "user" => "dev_user",
    ],
    "master" => [
        "host" => "192.168.0.1",
        "password" => "pwd_master",
    ],
    "cluster1" => [
        "host" => "192.168.0.1",
        "password" => "pwd_cluster1",
    ],
    "cluster2" => [
        "host" => "192.168.0.1",
        "port" => "3307",
        "password" => "pwd_cluster2",
    ],
];
```

default项目为默认配置

所有子项继承default项目配置, 同名key覆盖default


### 4. 代码

**Config 配置加载与读取**

```php
<?php
namespace xiaofeng;

require_once __DIR__ . "/../src/bootstrap.php";

Config::load(__DIR__ . "/Conf", "dev");
assert(Config::getAll());
assert(Config::get("store.mysql.master.port") === 3306);
assert(Config::get("store.mysql.cluster1.password") === "pwd_cluster1");
assert(Config::get("dict.App") === "Config");
Config::set("store.mysql.cluster1.password", "new_password");
assert(Config::get("store.mysql.cluster1.password") === "new_password");
Config::set("dict.x.y.not_exist", "hello");
assert(Config::get("dict.x.y.not_exist") === "hello");


Config::load(__DIR__ . "/Conf", "test");
assert(Config::getAll());
assert(Config::get("store.mysql.master.user") === "test_user");
assert(Config::get("dict.App") === "Config");

Config::load(__DIR__ . "/Conf", "product");
assert(Config::getAll());
assert(Config::get("store.mysql.master.user") === "product_user");
assert(Config::get("dict.App") === "Config");
```

**ConfigGen 对象配置生成器**

```php
<?php
namespace xiaofeng;

require_once __DIR__ . "/../src/bootstrap.php";


Config::load(__DIR__ . "/Conf", "dev");
assert(Config::getAll());

$conf = ConfigGen::requireOnce(Config::getAll(), __DIR__);
// 生成代码见下方

assert($conf);
assert($conf->store->mysql->master->port === 3306);
assert($conf->store->mysql->cluster1->password === "pwd_cluster1");
assert($conf->dict->App === "Config");
$conf->store->mysql->cluster1->password = "new_password";
assert($conf->store->mysql->cluster1->password === "new_password");

// 不支持设置新配置, 因为生成配置对象文件的初衷是为了IDE的代码提示!!!
//Config::set("dict.x.y.not_exist", "hello");
//assert(Config::get("dict.x.y.not_exist") === "hello");
```

**自动生成的代码**

```php
<?php
namespace xiaofeng;

final class ConfigObject_dict {

	public $App = 'Config';
	public $Version = '0.1';
	public $reference = 'Zan-config';
	public $coder = 'xiaofeng';

	public function __construct() {

	}
}

final class ConfigObject_store_mysql_master {

	public $port = 3306;
	public $user = 'dev_user';
	public $host = '192.168.0.1';
	public $password = 'pwd_master';

	public function __construct() {

	}
}

final class ConfigObject_store_mysql_cluster1 {

	public $port = 3306;
	public $user = 'dev_user';
	public $host = '192.168.0.1';
	public $password = 'pwd_cluster1';

	public function __construct() {

	}
}

final class ConfigObject_store_mysql_cluster2 {

	public $port = 3307;
	public $user = 'dev_user';
	public $host = '192.168.0.1';
	public $password = 'pwd_cluster2';

	public function __construct() {

	}
}

final class ConfigObject_store_mysql {

	public $master;
	public $cluster1;
	public $cluster2;

	public function __construct() {
		$this->master = new ConfigObject_store_mysql_master;
		$this->cluster1 = new ConfigObject_store_mysql_cluster1;
		$this->cluster2 = new ConfigObject_store_mysql_cluster2;
	}
}

final class ConfigObject_store {

	public $mysql;

	public function __construct() {
		$this->mysql = new ConfigObject_store_mysql;
	}
}

final class ConfigObject {

	public $dict;
	public $store;

	public function __construct() {
		$this->dict = new ConfigObject_dict;
		$this->store = new ConfigObject_store;
	}
}

return new ConfigObject;
```