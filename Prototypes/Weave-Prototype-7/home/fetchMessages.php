<?php
    require_once "../libraries/accounts.php";
    session_start();

    require_once "../libraries/messages.php";

    $channelId = $_GET["channelId"];
    $startIndex = $_GET["startIndex"];
    $endIndex = $_GET["endIndex"];

    echo json_encode(ChatInteractions::getMessagesByChannelId($channelId, $startIndex, $endIndex));