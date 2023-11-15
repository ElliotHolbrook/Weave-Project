<?php
	require_once "../libraries/accounts.php";
	require_once "../libraries/messages.php";
	
	$participants = json_decode(rawurldecode($_GET["participants"]));
	$name = rawurldecode($_GET["name"]);

	$participantIds = [];
	foreach($participants as $participant) {
		$parts = explode("#", $participant);
		if(count($parts) == 1) { return; };
		$username = $parts[0];
		$tag = $parts[1];
		$id = AccountInteractions::getIdByUsernameTag($username, $tag);
		if($id !== False) {
			array_push($participantIds, $id);
		}
	}

	ChatInteractions::createGroupChat($participantIds, $name);
