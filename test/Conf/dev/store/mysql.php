<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/6/25
 * Time: 下午4:29
 */

return [
    "share" => [
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
        "port" => 3307,
        "password" => "pwd_cluster2",
    ],
];