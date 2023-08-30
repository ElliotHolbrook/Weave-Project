<?php
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }

    AccountInteractions::cancelFriendRequestById(rawurldecode($_GET["id"]), $_SESSION["account"]->getId());
    echo True;