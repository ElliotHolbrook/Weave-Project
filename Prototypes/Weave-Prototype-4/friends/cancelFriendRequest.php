<?php
    require_once "../libraries/accounts.php";
    session_start();

    AccountInteractions::cancelFriendRequestById($_SESSION["account"]->getId(), rawurldecode($_GET["id"]));
    echo True;