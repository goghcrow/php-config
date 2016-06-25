<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/6/25
 * Time: 下午4:23
 */
namespace xiaofeng;

//opcache_reset();
error_reporting(E_ALL);
ini_set("display_startup_errors", true);
ini_set("display_errors", true);

require_once __DIR__ . "/../src/bootstrap.php";


Config::load(__DIR__ . "/Conf", "dev");
assert(Config::getAll());

$conf = ConfigGen::requireOnce(Config::getAll(), __DIR__);
assert($conf);
assert($conf->store->mysql->master->port === 3306);
assert($conf->store->mysql->cluster1->password === "pwd_cluster1");
assert($conf->dict->App === "Config");
$conf->store->mysql->cluster1->password = "new_password";
assert($conf->store->mysql->cluster1->password === "new_password");

// 不支持设置新配置, 因为生成配置对象文件的初衷是为了IDE的代码提示!!!
//Config::set("dict.x.y.not_exist", "hello");
//assert(Config::get("dict.x.y.not_exist") === "hello");
