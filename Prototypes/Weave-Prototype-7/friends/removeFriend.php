<?php
    require_once "../libraries/accounts.php";
    session_start();

    $id = rawurldecode($_GET["id"]);

    AccountInteractions::removeFriendById($_SESSION["account"]->getId(), $id);
    echo True;