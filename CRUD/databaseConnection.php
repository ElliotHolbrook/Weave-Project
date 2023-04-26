<?php
	//defining the values that we will need to connect
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "kayakraft_social";
	$charset = "utf8mb4";
	
	//using a try catch to echo any error so I know if I have made any mistakes
	try {
		//creating the database object that I can use for interractions with the database
		$db = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset", $username, $password);
		//setting the PDO error mode to exception so that I can echo it
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Error connecting to the database: " . $e->getMessage();
	}
	
	//allowing other php scripts to use the connection
	return $db;