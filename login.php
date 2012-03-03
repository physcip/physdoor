<?php
	function errorhandler($errno, $errstr)
	// Properly wrap error messages into JSON
	{
		if (error_reporting() != 0) // the statement that caused the error was not prepended by the @ error-control operator
			$GLOBALS['phperrors'][] = $errstr;
	}
	set_error_handler('errorhandler');
	
	require_once 'conf.inc.php';
	require_once 'ldap.inc.php';
	require_once 'db.inc.php';
	require_once 'netio.inc.php';
	require_once 'screenlock.inc.php';
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	
	$json = array();
	
	switch ($_GET['action'])
	{
		case 'login':
			$user = get_ldap_user($_POST['user']);
			if ($user !== FALSE && check_ldap_password($user[0]['dn'], $_POST['password']))
			{
				log_entry($user[0][ldap_uid][0],0);
				// screenlock('unlock');
				// open_door(netio_host, netio_port, netio_contact);
				$json['loggedin'] = TRUE;
			}
			else
			{
				$json['loggedin'] = FALSE;
				if ($user === FALSE)
					$json['error'] = 'Invalid user name';
				else
					$json['error'] = 'Invalid password';
			}
		break;
		case 'logout':
			if ($user = logged_on_user())
			{
				log_entry($user,1);
				// screenlock('lock');
			}
		break;
		case 'status':
			if ($user = logged_on_user()) // someone is logged in
			{
				$userinfo = get_ldap_user($user);
				$name = $userinfo[0]['cn'][0];
				
				$json['loggedin'] = TRUE;
				$json['name'] = $name;
			}
			else
			{
				$json['loggedin'] = FALSE;
			}
		break;
	}
	
	if (isset($phperrors))
	{
		if (!isset($json['error']))
			$json['error'] = "";
		$json['error'] .= '<br />' . implode('<br />', $phperrors);
	}
	
	echo json_encode($json);
?>