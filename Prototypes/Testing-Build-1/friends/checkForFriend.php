<?php
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }

    $username = rawurldecode($_GET["username"]);
    $tag = rawurldecode($_GET["tag"]);

    if(!AccountInteractions::checkForUsernameTag($username, $tag)) {
        echo "Friend Account Not Found";
        exit();
    }
    
    $id = AccountInteractions::getIdByUsernameTag($username, $tag);

    if (AccountInteractions::checkForIdInOutgoingFriendRequestsList($_SESSION["account"]->getId(), $id)) {
        echo "You have already sent a friend request to this person";
        exit();
    }

    if(AccountInteractions::checkForIdInFriendsList($_SESSION["account"]->getId(), $id)) {
        echo "This person is already one of your friends";
        exit();
    }

    if(AccountInteractions::checkForIdInIncomingFriendRequestsList($_SESSION["account"]->getId(), $id)) {
        echo "incomingFriend";
        exit();
    }
    
    if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){
        echo "You cannot add yourself as a friend";
        exit();
    } else {
        echo "Friend Account Found";
        exit();
    }