<?php
	define('ldap_base', 'dc=purple,dc=physcip,dc=uni-stuttgart,dc=de');
	define('ldap_server', 'ldaps://purple.physcip.uni-stuttgart.de/');
	define('ldap_uid', 'uid');
	//define('ldap_serviceuser', '');
	//define('ldap_servicepassword', '');
	
	define('netio_host', '129.69.74.200');
	define('netio_port', 50290);
	define('netio_contact', '4');
	
	define('screenlock_range', '129.69.74.132-154'); // multiple ranges can be comma-separated; individual IPs can also be added
	define('screenlock_port', 49777);
	
	define('mysql_host', '127.0.0.1');
	define('mysql_user', 'doorloggerz');
	//define('mysql_pass', '');
	define('mysql_db', 'door');
	
	define('websocket_port', 9988);
	
	@include_once 'conf_secret.inc.php';
?>