<?php
	function log_entry($username, $type) // 1=logout, 0=login
	{
		$db = new SQLite3(database);
		$stmt = $db->prepare('INSERT INTO AccessLog (username,date,type) VALUES (:user,DATETIME("now","localtime"),:type)');
		$stmt->bindValue(':user', $username, SQLITE3_TEXT);
		$stmt->bindValue(':type', $type,     SQLITE3_INTEGER);
		$stmt->execute();
	 	return TRUE;
	}
	
	function logged_on_user()
	{
		$db = new SQLite3(database);
		$arr = $db->querySingle('SELECT username,type FROM AccessLog ORDER BY id DESC LIMIT 1', TRUE);
		if ($arr['type'] == 0) // last entry was a login
			return ($arr['username']);
		else
			return FALSE;
	}
?>
