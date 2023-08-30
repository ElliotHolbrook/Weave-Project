<?php
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
        exit();
    }

    if ((isset($_GET["username"])) and (isset($_GET["tag"]))) {
        $username = rawurldecode($_GET["username"]);
        $tag = rawurldecode($_GET["tag"]);
        if(!AccountInteractions::checkForUsernameTag($username, $tag)) {
            echo False;
            exit();
        }
        if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){
            echo False;
            exit();
        } 
        $id = AccountInteractions::getIdByUsernameTag($username, $tag);
    } else {
        $id = rawurldecode($_GET["id"]);
    }
    
    if (AccountInteractions::checkForIdInOutgoingFriendRequestsList($_SESSION["account"]->getId(), $id)) {
        echo False;
        exit();
    }

    if (AccountInteractions::checkForIdInFriendsList($_SESSION["account"]->getId(), $id)) {
        echo False;
        exit();
    }
    
    if (AccountInteractions::checkForIdInIncomingFriendRequestsList($_SESSION["account"]->getId(), $id)) {
        AccountInteractions::becomeFriendsById($_SESSION["account"]->getId(), $id);
        echo True;
    } else {
        AccountInteractions::friendRequestById($_SESSION["account"]->getId(), $id);
        echo True;
    }