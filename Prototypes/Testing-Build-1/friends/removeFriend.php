<?php
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }

    $id = rawurldecode($_GET["id"]);

    AccountInteractions::removeFriendById($_SESSION["account"]->getId(), $id);
    echo True;