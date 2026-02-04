<?php
	$ROUTES = array
	(
		'default' => array('controller' => 'activities', 'action'=>'logbook'),
		'segments' => array('controller' => 'segments', 'action'=>'search'),
		'login' => array('controller' => 'users', 'action'=>'login'),
		'logout' => array('controller' => 'users', 'action'=>'logout'),
		'home' => array('controller' => 'activities', 'action'=>'alltime'),
		'dashboard' => array('controller' => 'activities', 'action'=>'dashboard'),
		'all' => array('controller' => 'activities', 'action'=>'logbook'),
		'logbook' => array('controller' => 'activities', 'action'=>'logbook'),
		'timeframe' => array('controller' => 'activities', 'action'=>'timeframes'),
		'search' => array('controller' => 'activities', 'action'=>'search'),
		'view' => array('controller' => 'activities', 'action'=>'view'),
		'sun' => array('controller' => 'pages', 'action'=>'sun'),
		'links' => array('controller' => 'links', 'action'=>'search')
	);
?>
