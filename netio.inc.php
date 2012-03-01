<?php
	function open_door($host, $port, $contact)
	{
		$fp = fsockopen($host, $port, $errno, $errstr, 5);
		if (!$fp)
		{
			//echo "<b>Error $errno:</b> $errstr";
			return FALSE;
		}
		else
		{
			$result = TRUE;
			fwrite($fp, "setport ".$contact.".1\n");
			$result = fgets($fp);
			if ($result == "ACK\r\n")
			{
				//echo "<p>Door opened</p>";
				sleep(5);
			}
			else
			{
				//echo "<p><b>Fehler:</b> $result</p>";
				$result = FALSE;
			}
			
			fwrite($fp, "setport ".$contact.".0\n");
			$result = fgets($fp);
			if ($result == "ACK\r\n")
			{
				//echo "<p>Door closed</p>";
			}
			else
			{
				//echo "<p><b>Error:</b> $result</p>";
				$result = FALSE;
			}
			
			fclose($fp);
			
			return $result;
		}
	}
?>