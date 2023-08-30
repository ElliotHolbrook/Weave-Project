<?php
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }

    AccountInteractions::cancelFriendRequestById($_SESSION["account"]->getId(), rawurldecode($_GET["id"]));
    echo True;