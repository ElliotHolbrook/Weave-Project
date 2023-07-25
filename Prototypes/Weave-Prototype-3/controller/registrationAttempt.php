<?php  
    require_once "../model/accounts.php";

    //get values and set em
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password-repeat"];
    $pin = $_POST["pin"];
    
    //generate the ID for the account
    $id = AccountFunctions::generateId();

    if (!AccountFunctions::validatePassword($password)) {
        header("Location:  ../login/register.php?error=invalid-password&username=$username&email=$email&pin=$pin");      //making sure that password fits within the security constraints
        exit();
    } else {
        $passHash = AccountFunctions::hashPassword($password);
    }
    if ($passwordRepeat != $password) {
        header("Location:  ../login/register.php?error=invalid-password-repeat&username=$username&email=$email&pin=$pin");   //make sure the password and password repeat are the same
        exit();
    }
    
    $account = new Account($username, $email, $passHash, $pin, $id);
    
    if (!$account->getUsernameSetResult()) {
        header("Location:  ../login/register.php?error=invalid-username&email=$email&pin=$pin");     //make sure the username is valid
        exit();
    }
    if (!$account->getEmailSetResult()) {
        header("Location:  ../login/register.php?error=invalid-email&username=$username&pin=$pin");      //make sure the email is valid
        exit();
    }
    if (!$account->getPinSetResult()) {
        header("Location:  ../login/register.php?error=invalid-pin&username=$username&email=$email");      //make sure the pin is valid
        exit();
    }

    //add backslashes to username before disruptive characters so that it displays properly
    addslashes($username);
    
    AccountInteractions::addAccountToDatabase($account);

    
