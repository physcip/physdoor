<?php
	function log_entry($username, $type) // 1=logout, 0=login
	{
		$conn = mysql_connect(mysql_host, mysql_user, mysql_pass);
	 	if ($conn)
  	 	{
			mysql_select_db(mysql_db, $conn);
			
			$error = FALSE;
			mysql_query("INSERT INTO AccessLog (username,date,type)
			VALUES ('" . mysql_real_escape_string($username) . "', now(), '" . mysql_real_escape_string($type) . "')", $conn) or $error = TRUE;
	
			mysql_close($conn);
			return $error;
		}
		return FALSE;
	}
	
	function logged_on_user()
	{
		$conn = mysql_connect(mysql_host, mysql_user, mysql_pass);
	 	if ($conn)
  	 	{
			mysql_select_db(mysql_db, $conn);
			
			$error = FALSE;
			$result = mysql_query("SELECT * FROM AccessLog ORDER BY id DESC LIMIT 1", $conn) or $error = TRUE;
			
			echo mysql_error();
	
			mysql_close($conn);
			if ($error == TRUE)
				return FALSE;
			
			$arr = mysql_fetch_array($result, MYSQL_ASSOC);
			if ($arr['type'] == 0) // last entry was a login
				return ($arr['username']);
			else
				return FALSE;
		}
		return FALSE;
	}
?>