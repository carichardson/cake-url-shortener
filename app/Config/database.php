<?php

class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '127.0.0.1',
		'login' => 'short_user',
		'password' => '0dvzdak97gali0i',
		'database' => 'short',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	public $local = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => '127.0.0.1',
		'login' => 'root',
		'password' => 'root',
		'database' => 'short',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	public function __construct()
	{
		switch(php_uname('n')) {
			default:
			$this->default = $this->default;
			break;
			case 'ed-chris.local':
			case 'ed-chris.home':
			$this->default = $this->local;
			break;
		}
	}
}