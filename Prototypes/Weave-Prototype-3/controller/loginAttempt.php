<?php   
    session_start();
    require_once "../model/accounts.php";
    
    //get inputs from user
    $email = $_POST["email"];
    $password = $_POST["password"];

    //make sure the email is an actual email. If it is not then the script will die and the user will be sent back to the log in page
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  header("Location:  ../login?error=invalid-email");
      exit();
	  }

    //get hashed password from database
    $passHashed = AccountInteractions::getPassHashedByEmail($email);
    //verify password
    if (password_verify($password, $passHashed)) {
		//sends uses to home page and sets session variable to the username
		  echo "Success";
      $_SESSION["username"] = AccountInteractions::getUsernameByEmail($email);
      AccountFunctions::setLogInSessionVarsByEmail($email);
		  header("Location: ../home");
	  } else {
		//sends user back to the log in page
		  echo "Failure";
		  header("Location:  ../login?error=invalid-password-email");
  	}