<?php

	Router::connect('/', array('controller' => 'shorten', 'action' => 'index'));
	Router::connect('/shorten', array('controller' => 'shorten', 'action' => 'index'));
	Router::connect('/stats', array('controller' => 'stats', 'action' => 'index'));
	Router::connect('/redirect/record_stats', array('controller' => 'redirect', 'action' => 'record_stats'));
	Router::connect('/:shortcode', array('controller' => 'redirect', 'action' => 'index'), array('pass' => array('shortcode')));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	CakePlugin::routes();

	require CAKE . 'Config' . DS . 'routes.php';
