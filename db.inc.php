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
?>