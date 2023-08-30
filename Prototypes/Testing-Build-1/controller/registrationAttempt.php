<?php  
    require_once "../libraries/accounts.php";

    //get all the values that will be required
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password-repeat"];
    $pin = $_POST["pin"];
    
    //generate the ID for the account
    $id = AccountFunctions::generateId();
    $tag = AccountFunctions::generateTagForUsername($username);

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
    if (AccountInteractions::checkForEmail($email)) {       //make sure the email has not been used before
        header("Location:  ../login/register.php?error=invalid-email&username=$username&pin=$pin");
    }
    
    $account = new Account(username: $username, tag: $tag, email: $email, passHashed: $passHash, pin: $pin, id: $id);
    
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

    session_start();

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
        exit();
    }
    AccountInteractions::addAccountToDatabase($account);

    $_SESSION["email"] = $email;
    $_SESSION["username"] = $username;
    $_SESSION["tag"] = $account->getTag();
    $_SESSION["pin"] = $pin;
    $_SESSION["id"] = $id;
    $_SESSION["account"] = $account;
    
    header("Location: ../home");