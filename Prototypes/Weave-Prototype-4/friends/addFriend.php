<?php
    require_once "../libraries/accounts.php";
    session_start();

    $username = $_GET["username"];
    $tag = $_GET["tag"];

    if(!AccountInteractions::checkForUsernameTag($username, $tag)) {
        echo False;
        exit();
    }
    
    if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){
        echo False;
    } else {
        $friendId = AccountInteractions::getIdByUsernameTag($username, $tag);

        AccountInteractions::addFriendToAccountById($_SESSION["account"]->getId(), $friendId);
        AccountInteractions::addFriendToAccountById($friendId, $_SESSION["account"]->getId());
        
        echo True;
    }