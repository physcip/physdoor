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
			if ($user !== FALSE && check_ldap_password($user[0]['dn'], $_POST['password']) && !in_array($user[0]['uid'][0], $deny_users))
			{
				log_entry($user[0][ldap_uid][0],0);
				open_door(netio_host, netio_port, netio_contact);
				$json['loggedin'] = TRUE;
				
				$name = $user[0]['cn'][0];
				$json['name'] = $name;
			}
			else
			{
				$json['loggedin'] = FALSE;
				if ($user === FALSE)
					$json['error'] = 'Invalid user name';
				elseif (in_array($user[0]['uid'][0], $deny_users))
					$json['error'] = 'User not allowed to log in';
				else
					$json['error'] = 'Invalid password';
			}
		break;
		case 'logout':
			if ($user = logged_on_user())
			{
				log_entry($user,1);
				$json['loggedin'] = FALSE;
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
		case 'lock':
			screenlock('lock');
			$json['screenlock'] = 1;
		break;
		case 'unlock':
			screenlock('unlock');
			$json['screenlock'] = 0;
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