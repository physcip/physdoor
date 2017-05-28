<?php
	$allowedclients = array('door-pc-1.physcip.uni-stuttgart.de', 'door-pc-2.physcip.uni-stuttgart.de', 'localhost', 'localhost.localdomain');
	if (php_sapi_name() != 'cli' && !in_array(gethostbyaddr($_SERVER['REMOTE_ADDR']), $allowedclients))
		die('Unauthorized');
	
	define('ldap_base', 'dc=physcip,dc=uni-stuttgart,dc=de');
	define('ldap_server', 'ldaps://dc01.physcip.uni-stuttgart.de ldaps://dc02.physcip.uni-stuttgart.de');
	define('ldap_uid', 'uid');
	//define('ldap_serviceuser', '');
	//define('ldap_servicepassword', '');
	@include 'conf_secret.inc.php';
	
	define('netio_host', 'vm02.physcip.uni-stuttgart.de');
	define('netio_port', 50290);
	define('netio_contact', '4');

	// DFR0222 "physdoor-relay" board that closes door opener contact
	define('relay_host', 'physdoor-relay.physcip.uni-stuttgart.de');
	define('relay_auth_timeframe', 50);

	define('screenlock_range', '129.69.74.132-155'); // multiple ranges can be comma-separated; individual IPs can also be added
	define('screenlock_port', 49777);
	
	define('database', 'db/door.db');
	
	define('websocket_port', 9988);
	
	define('master_name', 'door-pc-2.physcip.uni-stuttgart.de');
	$outside_names = array();
	$notouch_names = array('door-pc-1.physcip.uni-stuttgart.de');
	$anonymize_names = array('door-pc-2.physcip.uni-stuttgart.de');
	
	$deny_users = array('physreg');
?>
