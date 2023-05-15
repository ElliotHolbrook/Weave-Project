<?php
    session_start();

    //setting the information about the message
    $text = $_POST["message-input"];
    $senderId = $_SESSION["id"];
    //setting the time that it was sent in the format year month day hour minute second
    $datetime = date("YmdHis");

