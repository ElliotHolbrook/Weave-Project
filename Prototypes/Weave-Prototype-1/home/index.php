<?php
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: ../login");
    }
?>
<!DOCTYPE html>
<html>
    <h1>Home</h1><br>
    <form>
        <label for="message-input">Type your message here: </label>
        <input type="text" id="message-input" name="message-input"></input> <br>
        <input type="submit"></input>
    </form>
    <br>
    
    Username: <?php    echo $_SESSION["username"]; ?>
    <form action="logOut.php" method="get">
        <button type="submit">Log Out</button>
    </form>
</html>