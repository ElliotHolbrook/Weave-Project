<?php
    require_once "../libraries/accounts.php";
    session_start();

    $username = $_GET["username"];
    $tag = $_GET["tag"];

    if(!AccountInteractions::checkForUsernameTag($username, $tag)) {
        echo "Friend Account Not Found";
        exit();
    }
    
    if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){
        echo "You cannot add yourself as a friend";
    } else {
        echo "Friend Account Found";
    }