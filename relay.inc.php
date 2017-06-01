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
		$curl = curl_init("http://" . relay_host . "/" . $action);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $hash);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);

		return $response === "ok\r\n";
	}

	function relay_open_door() {
		return relay_action("open");
	}
?>
