<?php
	$socket = socket_create(AF_INET, SOCK_STREAM, 0);
	socket_bind($socket, "127.0.0.1", 8080);
	$help = socket_listen($socket, int $backlog = 0);
	echo $help;
	print_r($socket);