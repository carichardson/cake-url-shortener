<?php
	// copied from webroot/index.php
	
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}

	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}

	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}

	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}

	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		if (function_exists('ini_set')) {
			ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
		}
		if (!include ('Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	} else {
		if (!include (CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	}
	if (!empty($failed)) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}

	App::uses('Dispatcher', 'Routing');

	define('CRON_DISPATCHER',true);
	$Dispatcher = new Dispatcher();
	$Dispatcher->dispatch(new CakeRequest($argv[1]), new CakeResponse(array('charset' => Configure::read('App.encoding'))));