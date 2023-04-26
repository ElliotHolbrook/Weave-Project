<?php
	//get database connection
	$db = require "databaseConnection.php";
	
	//get data from the log in form
	$email = $_GET["email"];
	$password = $_GET["password"];
	
	//prepare SQL statement
	$sql = "SELECT passHashed FROM account_data WHERE email='{$email}'";
	//prepare operation and point at db
	$stmt = $db->prepare($sql);
	//execute operation
	$stmt->execute();
	//fetch data from database
	$passHashed = $stmt->fetch();
	
	
	//verify user password against hashed database password
	if (password_verify($password, $passHashed["passHashed"])) {
		echo "Password Correct";
	} else {
		echo "Password Not Correct";
	}