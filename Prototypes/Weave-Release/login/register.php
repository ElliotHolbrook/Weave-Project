<!DOCTYPE html>
<?php
    session_start();

    if (isset($_SESSION["username"])) {
        header("Location: ../home");
    }

?>    
    <html>
        <head>
            <title>Weave Registration Page</title>
        </head>
        <body>
            <h1>Register</h1><br>
            <form id="registration-form" action="../controller/registrationAttempt.php" method="post">
                <label for="username">Username: </label><input name="username" type="text" value="<?php if(isset($_GET["username"])) {echo $_GET["username"];} ?>" required></input><br>
                    <ul>
                        <li>Must be at least 4 characters</li>
                    </ul>
                <label for="email">Email: </label><input name="email" type="text" value="<?php if(isset($_GET["email"])) {echo $_GET["email"];} ?>" required></input><br>
                    <ul>
                        <li>Must be a valid email</li>
                        <li>Must not already be associated with a Weave Account</li>
                    </ul>
                <label for="password">Password: </label><input name="password" type="password" minlength="6" required></input><br>
                    <ul>
                        <li>Must be a at least 7 characters long</li>
                        <li>Must contain at least one upper case letter</li>
                        <li>Must contain at least one lower case letter</li>
                        <li>Must contain at least one symbol</li>
                    </ul>
                <label for="password-repeat">Password Repeat: </label><input name="password-repeat" type="password" required></input><br>
                <ul>
                    <li>Must be identical to the password</li>
                </ul>
                <label for="pin">Pin: </label><input name="pin" type="number" step="1" minlength="4" maxlength="4" value="<?php if(isset($_GET["pin"])) {echo $_GET["pin"];} ?>" required></input><br>
                <ul>
                    <li>Must be a 6 digit number</li>
                </ul>
                <button type="submit">Register</button>
            </form>
            <br><br>Already have an account? Click <a href="index.php">here</a>
        </body>
    </html>