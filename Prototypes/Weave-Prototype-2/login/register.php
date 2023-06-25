<!DOCTYPE html>
<?php
    session_start();

    
    if (isset($_SESSION["username"])) {
        header("Location: ../home");
    }
?>    
    <html>
        <head>
        </head>
        <body>
            <h1>Register</h1><br>
            <form id="registration-form" action="../controller/registrationAttempt.php" method="post">
                <label for="username">Username: </label><input name="username" type="text" required></input><br>
                <label for="email">Email: </label><input name="email" type="text" required></input><br>
                <label for="password">Password: </label><input name="password" type="password" minlength="6" required></input><br>
                <label for="password-repeat">Password Repeat: </label><input name="password-repeat" type="password" required></input><br>
                <label for="pin">Pin: </label><input name="pin" type="number" step="1" minlength="4" maxlength="4" required></input><br>
                <button type="submit">Register</button>
            </form>
            <br><br>Already have an account? Click <a href="index.php">here</a>
        </body>
    </html>