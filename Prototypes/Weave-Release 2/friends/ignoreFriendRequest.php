<?php
    require_once "../libraries/accounts.php";
    session_start();

    AccountInteractions::cancelFriendRequestById(rawurldecode($_GET["id"]), $_SESSION["account"]->getId());
    echo True;