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