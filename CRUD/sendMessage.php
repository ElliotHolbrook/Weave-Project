<?php
    $db = require "databaseConnection.php";

    $message = $_GET["message"];

    $sql = "INSERT INTO messages ("textContents") VALUES ($message)";
    $stmt = $db->prepare("$sql");
    $stmt->execute();

