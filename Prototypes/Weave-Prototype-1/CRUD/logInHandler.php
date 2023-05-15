<?php
    session_start();
	
	$db = require_once "accountsDatabaseAccess.php";

    //get inputs from users
	$email = $_POST["email"];
    $password = $_POST["password"];

    //check the email is an email rather than anything malicious
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location:  ../login");
	}
	//prepare SQL statement
	$sql = "SELECT * FROM account_data WHERE email = :email";
	//prepare operation and point at db
	$stmt = $db->prepare($sql);
    //replace :email with the inputted email
    $stmt->bindParam(":email", $email);
	//execute operation
	$stmt->execute();
	//fetch data from database
	$userData = $stmt->fetch();

	//verify user password against hashed database password
	if (password_verify($password, $userData["passHashed"])) {
		//sends uses to home page and sets session variable to the username
		echo "Success";
		setSessionVars($userData);
		header("Location: ../home");
	} else {
		//sends user back to the log in page
		echo "Failure";
		header("Location:  ../login");
	}

	function setSessionVars($userData) {
		$_SESSION["username"] = $userData["username"];
		$_SESSION["id"] = $userData["id"];
	}
