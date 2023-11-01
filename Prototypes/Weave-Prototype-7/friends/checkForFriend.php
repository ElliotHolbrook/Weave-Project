<?php
    require_once "../libraries/accounts.php";
    session_start();

    $username = rawurldecode($_GET["username"]);
    $tag = rawurldecode($_GET["tag"]);

    if(!AccountInteractions::checkForUsernameTag($username, $tag)) {    //Check to make sure account exists
        echo "Friend Account Not Found";
        exit();
    }
    
    $id = AccountInteractions::getIdByUsernameTag($username, $tag);     //if friend account exists then get id of friend

    if (AccountInteractions::checkForIdInOutgoingFriendRequestsList($_SESSION["account"]->getId(), $id)) {         //check if friend request already sent
        echo "You have already sent a friend request to this person";
        exit();
    }

    if(AccountInteractions::checkForIdInFriendsList($_SESSION["account"]->getId(), $id)) {      //check if already a friend
        echo "This person is already one of your friends";
        exit();
    }

    if(AccountInteractions::checkForIdInIncomingFriendRequestsList($_SESSION["account"]->getId(), $id)) {       //check if user has sent a friend request already
        echo "incomingFriend";
        exit();
    }
    
    if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){       //check if the friend is the user
        echo "You cannot add yourself as a friend";
        exit();
    } else {
        echo "Friend Account Found";            //if all conditions met then return friend account found
        exit();
    }