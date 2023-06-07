<?php
    session_start();

    $db = require_once();
    //setting the information about the message
    $text = $_POST["message-input"];
    $senderId = $_SESSION["id"];
    //setting the time that it was sent in the format year month day hour minute second
    $datetime = date("YmdHis");
    //setting the default status of whether the message was edited after being sent
    $edited = FALSE;


