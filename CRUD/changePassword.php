<?php
	$db = require "databaseConnection.php";
	
	$password = "password";
	
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	$sql = "UPDATE account_data SET passHashed = ('{$hashedPassword}') WHERE email='email@mail.com'";
	$stmt = $db->prepare($sql);
	$stmt->execute();