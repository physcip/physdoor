<?php
	require_once "conf.inc.php";

	set_time_limit(long_polling_interval);
	$request_time = time();

	for (;;) {
		clearstatcache();
		$last_db_update = filemtime(database);

		if ($last_db_update > $request_time) {
			echo "update";
			break;
		} else {
			usleep(0.1 * 1000000);
			continue;
		}
	}	
?>
