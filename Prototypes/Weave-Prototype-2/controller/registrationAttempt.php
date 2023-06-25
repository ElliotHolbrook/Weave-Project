<?php  
    require_once "../model/accounts.php";

    //get values and set em
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password-repeat"];
    $pin = $_POST["pin"];

    if (strlen($username) < 4) {
        header("Location:  ../login/register.php?error=invalid-username");     //make sure the username is a valid length
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location:  ../login/register.php?error=invalid-email");     //make sure the email is valid
        exit();
    } elseif (!AccountFunctions::validatePassword($password)) {
        header("Location:  ../login/register.php?error=invalid-password");      //making sure that password fits within the security constraints
        exit();
    } elseif ($passwordRepeat != $password) {
        header("Location:  ../login/register.php?error=invalid-password-repeat");   //make sure the password and password repeat are the same
        exit();
    } elseif (!AccountFunctions::validatePin($pin)) {
        header("Location:  ../login/register.php?error=invalid-pin");     //make sure the email is valid
        exit();
    }
    //add backslashes to username before disruptive characters so that it displays properly
    addslashes($username);

    $account = new Account($username, $email, $password, $pin);
    AccountInteractions::addAccountToDatabase($account);


