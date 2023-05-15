<?php
    session_start();

    
    if (isset($_SESSION["username"])) {
        header("Location: ../home");
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Weave ProtoType 1 Log In Page</title>
        </head>
        <body>
            <h1>Log In</h1><br>
            <form id="logInForm" action="../CRUD/logInHandler.php" method="post">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required autofocus></input><br>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required></input><br>
                <!--<button onclick="logInAttempt()">Submit</button>-->
                <input type="submit">
            </form>
        </body>

        <!--<script src="javascript/logInScript.js"></script>-->
    </html>
