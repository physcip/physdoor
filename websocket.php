#!/usr/bin/env php
<?php
	chdir(dirname(__FILE__));
	
	require_once 'WebSockets/WebSocket/Server.php';
	require_once 'WebSockets/WebSocket/User.php';
	require_once 'WebSockets/WebSocket/Frame.php';
	
	class wsChat extends WebSocket\Server
	{
		protected function gotText(WebSocket\User $user, $data)
		{
			socket_getpeername($user->getSocket(), $addr, $port);
			
			echo "Received '$data' from $addr:$port\n";
			$this->sendTextToAll($addr . ': ' . $data);
		}
	}
	
	$server = new wsChat('127.0.0.1', 9988);
	$server->process();
?>