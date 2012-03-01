<?php
	function get_ldap_user($user, $server)
	{
		$conn = ldap_connect($server);
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if (defined("serviceuser"))
			$bind = ldap_bind($conn, serviceuser, servicepassword);
		else
			$bind = ldap_bind($conn);
		if ($bind)
		{
			$result = ldap_search($conn, ldap_base, '(&(' . ldap_uid . '=' . $user . '))');
			$info = ldap_get_entries($conn, $result);
		}
		else
			$info = NULL;
		ldap_close($conn);
		return $info;
	}
	
	function check_ldap_password($user, $password, $server)
	{
		$conn = ldap_connect($server);
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$bind = @ldap_bind($conn, $user, $password);
		if ($bind)
			$result = TRUE;
		else
			$result = FALSE;
		ldap_close($conn);
		return $result;
	}
?>