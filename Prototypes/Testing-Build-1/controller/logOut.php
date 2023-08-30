<?php
session_start();

if(!isset($_SESSION["tester"])) {
    header("Location: ../index.php");
    exit();
}
session_unset(); 
header("Location: ../login");