<?php
    require_once "../libraries/accounts.php";
    session_start();

    if ((isset($_GET["username"])) and (isset($_GET["tag"]))) {     //checks to make sure username and tag both recieved
        $username = rawurldecode($_GET["username"]);                   //gets data
        $tag = rawurldecode($_GET["tag"]);
        if(!AccountInteractions::checkForUsernameTag($username, $tag)) {        //makes sure account exists
            echo False;
            exit();
        }
        if (($username == $_SESSION["account"]->getUsername()) and ($tag == $_SESSION["account"]->getTag())){       //checks to make sure sender and reciever aren't the same
            echo False;
            exit();
        } 
        $id = AccountInteractions::getIdByUsernameTag($username, $tag);         //if all okay then gets ID of friend
    } elseif(isset($_GET["id"])) {
        $id = rawurldecode($_GET["id"]);        //if username and tag not set then use id as sent with GET
    } else {
        echo False;
        exit();
    }
    
    if (AccountInteractions::checkForIdInOutgoingFriendRequestsList($_SESSION["account"]->getId(), $id)) {      //check if friend request sent to friend already
        echo False;
        exit();
    }

    if (AccountInteractions::checkForIdInFriendsList($_SESSION["account"]->getId(), $id)) {         //check if already friends
        echo False;
        exit();
    }
    
    if (AccountInteractions::checkForIdInIncomingFriendRequestsList($_SESSION["account"]->getId(), $id)) {      //check if friend has sent user a friend request already
        AccountInteractions::becomeFriendsById($_SESSION["account"]->getId(), $id);                             //add the users as friends
        echo True;
    } else {
        AccountInteractions::friendRequestById($_SESSION["account"]->getId(), $id);                   //else send a friend request
        echo True;
    }