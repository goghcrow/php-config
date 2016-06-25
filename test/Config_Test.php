<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/6/25
 * Time: 下午4:23
 */
namespace xiaofeng;

// opcache_reset();

ini_set("display_startup_errors", true);
ini_set("display_errors", true);
error_reporting(E_ALL);


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
