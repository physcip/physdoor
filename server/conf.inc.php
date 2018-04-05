<?php
	$allowedclients = array("door-pc-1.physcip.uni-stuttgart.de", "door-pc-2.physcip.uni-stuttgart.de", "localhost", "localhost.localdomain");
	if (php_sapi_name() != "cli" && !in_array(gethostbyaddr($_SERVER["REMOTE_ADDR"]), $allowedclients))
		die("Unauthorized");

	/*
	 * LDAP Configuration
	 */
	define("ldap_base", "dc=physcip,dc=uni-stuttgart,dc=de");
	define("ldap_server", "ldaps://dc01.physcip.uni-stuttgart.de ldaps://dc02.physcip.uni-stuttgart.de");
	define("ldap_uid", "uid");

	@include "conf_secret.inc.php";

	/*
	 * DFR0222 "physdoor-relay" board Configuration
	 * (board that closes door opener contact)
	 */
	define("relay_host", "physdoor-relay.physcip.uni-stuttgart.de");
	define("relay_auth_timeframe", 50);

	/*
	 * Screenlock Configuration
	 */
	// multiple ranges can be comma-separated; individual IPs can also be added
	// TODO: define("screenlock_range", "129.69.74.132-155");
	// for testing: just tywin
	define("screenlock_range", "129.69.74.156");
	define("screenlock_port", 49777);

	/*
	 * Database Configuration
	 */
	define("database", "db/door.db");

	/*
	 * Primary / Secondary display configuration
	 * One client (identified by host address) can be configured as the primary device.
	 * Only that computer displays the login / logout form. All other computers just display
	 * the login / logout status. The primary doesn't show the user's full name while the others do.
	 */
	define("primary_name", "door-pc-2.physcip.uni-stuttgart.de");
	define("primary_page", "client/primary.html");
	define("secondary_page", "client/secondary.html");

	/*
	 * Long polling interval (seconds) - secondary watches for status updates using long polling
	 */
	define("long_polling_interval", 4 * 60);
?>
