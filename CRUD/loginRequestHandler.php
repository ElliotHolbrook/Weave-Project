<?php
	//get database connection
	$db = require "databaseConnection.php";
	
	//get data from the log in form
	$email = $_GET["email"];
	$password = $_GET["password"];
	
	//prepare SQL statement
	$sql = "SELECT * FROM account_data WHERE email='{$email}'";
	//prepare operation and point at db
	$stmt = $db->prepare($sql);
	//execute operation
	$stmt->execute();
	//fetch data from database
	$userData = $stmt->fetch();
	
	
	//verify user password against hashed database password
	if (password_verify($password, $userData["passHashed"])) {
		//sends uses to home page
		echo "Success";
		header("Location: ../home");
	} else {
		//sends user back to the log in page
		echo "Failure";
		header("Location:  ../login");
	}