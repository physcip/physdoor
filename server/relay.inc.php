<?php
	require_once("Sha3.php");
	require_once("conf.inc.php");
	require_once("conf_secret.inc.php");

	function relay_action($action) {
		# Generate key according to https://github.com/physcip/physdoor-relay/ and send request
		$timestamp = floor(time() / relay_auth_timeframe) * relay_auth_timeframe;
		$concat = relay_password . $timestamp;
		$hash = bb\Sha3\Sha3::hash($concat, 256);

		# Send HTTP POST request with key as POST data
		$options = array(
			"http" => array(
				"header" => "Content-type: text/plain\r\n",
				"method" => "POST",
				"content" => $hash
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents("http://" . relay_host . "/" . $action, false, $context);
		return $result === "ok\r\n";
	}

	function relay_open_door() {
		return relay_action("open");
	}
?>
