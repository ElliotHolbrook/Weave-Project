<?php
    require_once "../libraries/accounts.php";
    session_start();

    require_once "../libraries/messages.php";

    $channelId = $_GET["channelId"];
    $startIndex = $_GET["startIndex"];
    $endIndex = $_GET["endIndex"];

    $messages = ChatInteractions::getMessagesByChannelId($channelId, $startIndex, $endIndex);

    $usernameMessages = [];
    foreach($messages as $message){
        $message["senderUsername"] = AccountInteractions::getAccountById($message["senderId"])->getUsername();
        array_push($usernameMessages, $message);
    }

    echo json_encode($usernameMessages);