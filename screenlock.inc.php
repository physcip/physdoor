<?php
	function screenlock($type) // lock or unlock
	{
		$ips = array();
		$ranges = explode(',', screenlock_range);
		foreach ($ranges as $range)
		{
			$range = explode('-', $range);
			if (count($range) == 2)
				list($start, $last) = $range;
			elseif (count($range) == 1)
			{
				$start = $range[0];
				$last = end(explode('.', $range[0]));
			}	
			$start = explode('.', $start);
			$first = array_pop($start);
			$base = implode('.', $start);
			
			for ($i = $first; $i <= $last; $i++)
				$ips[] = $base . '.' . $i;
		}
		
		foreach ($ips as $ip)
		{
			$errorcount = 0;
			$fp = fsockopen("udp://" . $ip, screenlock_port, $errno, $errstr);
			if (!$fp) {
				//echo "ERROR for $i: $errno - $errstr<br />\n";
				$errorcount++;
			} else {
				fwrite($fp, $type . "\n");
				fclose($fp);
			}
		}
		
		if ($errorcount == 0)
			return TRUE;
		return FALSE;
	}
?>