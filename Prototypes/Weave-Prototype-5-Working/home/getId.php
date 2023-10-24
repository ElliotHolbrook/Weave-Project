<?php
    require_once "../libraries/accounts.php";
    session_start();
    
    echo $_SESSION["account"]->getId();