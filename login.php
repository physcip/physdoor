<?php
	// TODO: Before printing JSON, check whether any errors were printed
	
	require_once 'conf.inc.php';
	require_once 'ldap.inc.php';
	require_once 'db.inc.php';
	require_once 'netio.inc.php';
	require_once 'screenlock.inc.php';
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	
	switch ($_GET['action'])
	{
		case 'login': // untested
			$user = get_ldap_user($_POST['user']);
			if ($user !== FALSE && check_ldap_password($user[0]['dn'], $_POST['password']))
			{
				log_entry($user[0][ldap_uid][0],0);
				// screenlock('unlock');
				// open_door(netio_host, netio_port, netio_contact);
				echo json_encode(TRUE);
			}
			else
			{
				if ($user === FALSE)
					echo json_encode(array('error' => 'Invalid user name'));
				else
					echo json_encode(array('error' => 'Invalid password'));
			}
		break;
		case 'logout': // untested
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
				echo json_encode(array('name' => $name));
			}
			else
			{
				echo json_encode(FALSE);
			}
		break;
	}
?>