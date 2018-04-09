<?php
	require_once "conf.inc.php";

	set_time_limit(long_polling_interval + 1);
	$request_time = time();
	$seconds_since_request = 0;

	for (;;) {
		clearstatcache();
		$last_db_update = filemtime(database);

		if ($last_db_update > $request_time) {
			echo "update";
			break;
		} else {
			usleep(0.5 * 1000000);
			$seconds_since_request += 0.5;
			if ($seconds_since_request > long_polling_interval) {
				http_response_code(408);
				break;
			}
		}
	}	
?>
