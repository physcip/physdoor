<?php
	// Primary / Secondary client redirection
	include_once 'server/conf.inc.php';
	$is_primary = gethostbyaddr($_SERVER['REMOTE_ADDR']) == primary_name;

	header("Location: " . ($is_primary ? primary_page : secondary_page), true, 302);
	exit();
?>
