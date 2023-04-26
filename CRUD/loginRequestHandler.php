<?php
	$db = require "databaseConnection.php";
	
	$email = $_GET["email"];
	$password = $_GET["password"];
		
	echo $email . "<br>";
	echo $password . "<br>";
	
	$sql = "SELECT passHashed FROM account_data WHERE email='{$email}'";
	$stmt = $db->prepare($sql);
	$passHashed = $stmt->fetch();
	
	if (password_verify($password, $passHashed)) {
		echo "Password Correct";
	} else {
		echo "Password Not Correct";
	}