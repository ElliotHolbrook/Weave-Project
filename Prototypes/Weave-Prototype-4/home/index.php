<?php
    require_once "../libraries/accounts.php";
    session_start();
    if (!isset($_SESSION["account"])) {
        header("Location: ../login");
    }
?>
<!DOCTYPE html>
<html>
    <h1>Home</h1><br>
    <form action="" method="post">
        <label for="message-input">Type your message here: </label>
        <input type="text" id="message-input" name="message-input" autocomplete="off"></input> <br>
        <input type="submit"></input>
    </form>
    <br>
    
    Username: <?php    echo $_SESSION["account"]->getUsername(); ?><br>

    <a href="../friends/friend-manager.php">Manage Friends</a><br><br>
    
    <form action="../controller/logOut.php" method="get">
        <button type="submit">Log Out</button>
    </form>
</html>