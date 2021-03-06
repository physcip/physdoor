<?php
	function screenlock($type) // lock or unlock
	{
		$ips = array();
		$ranges = explode(",", screenlock_range);
		foreach ($ranges as $range)
		{
			$range = explode("-", $range);

			if (count($range) == 2)
				list($start, $last) = $range;
			elseif (count($range) == 1) {
				$start = $range[0];
				$ip4_octets = explode(".", $range[0]);
				$last = end($ip4_octets);
			}

			$start = explode(".", $start);
			$first = array_pop($start);
			$base = implode(".", $start);

			for ($i = $first; $i <= $last; $i++)
				$ips[] = $base . "." . $i;
		}

		foreach ($ips as $ip) {
			$errorcount = 0;
			$fp = fsockopen("udp://" . $ip, screenlock_port, $errno, $errstr);
			if (!$fp) {
				//echo "ERROR for $i: $errno - $errstr<br />\n";
				$errorcount++;
			} else {
				if (@fwrite($fp, $type . "\n") === FALSE)
					$errorcount++;
				fclose($fp);
			}
		}

		if ($errorcount == 0)
			return TRUE;

		return FALSE;
	}
?>
