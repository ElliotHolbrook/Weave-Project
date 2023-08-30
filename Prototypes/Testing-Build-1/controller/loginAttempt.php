<?php   
    require_once "../libraries/accounts.php";
    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }
    
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
      $account = AccountInteractions::getAccountByEmail($email);
      $_SESSION["email"] = $email;
      $_SESSION["username"] = $account->getUsername();
      $_SESSION["tag"] = $account->getTag();
      $_SESSION["pin"] = $account->getPin();
      $_SESSION["id"] = $account->getId();
      $_SESSION["account"] = $account;
		  header("Location: ../home");
	  } else {
		//sends user back to the log in page
		  echo "Failure";
		  header("Location:  ../login?error=invalid-password-email");
  	}