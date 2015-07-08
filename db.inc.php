<?php
	function log_entry($username, $type) // 1=logout, 0=login
	{
		$conn = mysqli_connect(mysql_host, mysql_user, mysql_pass, mysql_db);
	 	if ($conn)
		{
			$error = FALSE;
			mysqli_query($conn, "INSERT INTO AccessLog (username,date,type)
			VALUES ('" . mysqli_real_escape_string($conn, $username) . "', now(), '" . mysqli_real_escape_string($conn, $type) . "')") or $error = TRUE;
	
			mysqli_close($conn);
			return $error;
		}
		return FALSE;
	}
	
	function logged_on_user()
	{
		$conn = mysqli_connect(mysql_host, mysql_user, mysql_pass, mysql_db);
		if ($conn)
		{
			$error = FALSE;
			$result = mysqli_query($conn, "SELECT * FROM AccessLog ORDER BY id DESC LIMIT 1") or $error = TRUE;
			
			if ($error == TRUE)
				return FALSE;
			
			$arr = mysqli_fetch_array($result, MYSQLI_ASSOC);
			mysqli_close($conn);
			if ($arr['type'] == 0) // last entry was a login
				return ($arr['username']);
			else
				return FALSE;
		}
		return FALSE;
	}
?>
