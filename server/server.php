<?php
	// Properly wrap error messages into JSON
	function errorhandler($errno, $errstr) {
		if (error_reporting() != 0) // the statement that caused the error was not prepended by the @ error-control operator
			$GLOBALS["phperrors"][] = $errstr;
	}
	set_error_handler("errorhandler");

	require_once "conf.inc.php";
	require_once "ldap.inc.php";
	require_once "db.inc.php";
	require_once "screenlock.inc.php";
	require_once "relay.inc.php";

	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Content-type: application/json");

	$json = array();

	switch ($_GET["action"]) {
		case "login":
			$user = get_ldap_user($_POST["user"]);
			if ($user !== FALSE && check_ldap_password($user[0]["dn"], $_POST["password"])) {
				log_entry($user[0][ldap_uid][0],0);
				// TODO: relay_open_door();

				$json["loggedin"] = TRUE;
				
				$name = $user[0]["cn"][0];
				$json["name"] = $name;
			} else {
				$json["loggedin"] = FALSE;

				if ($user === FALSE)
					$json["error"] = "Invalid user name";
				else
					$json["error"] = "Invalid password";
			}
		break;

		case "logout":
			if ($user = logged_on_user()) {
				log_entry($user, 1);
				$json["loggedin"] = FALSE;
			} else {
				$json["error"] = "Can't logout - Nobody is currently logged in";
			}
		break;

		case "status":
			// someone is logged in
			if ($user = logged_on_user()) {
				$userinfo = get_ldap_user($user);
				$name = $userinfo[0]["givenname"][0] . " " . $userinfo[0]["sn"][0];
				$json["loggedin"] = TRUE;
				$json["name"] = $name;
			} else {
				$json["loggedin"] = FALSE;
			}
		break;

		// Authenticity is checked by only allowing requests from specific hosts
		case "lock":
			screenlock("lock");
			$json["screenlock"] = 1;
		break;

		case "unlock":
			screenlock("unlock");
			$json["screenlock"] = 0;
		break;
	}

	if (isset($phperrors)) {
		if (!isset($json["error"]))
			$json["error"] = "";
		$json["error"] .= "\n" . implode("\n", $phperrors);
	}

	echo json_encode($json);
?>
