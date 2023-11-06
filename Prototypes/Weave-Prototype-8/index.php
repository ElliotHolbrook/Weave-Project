<DOCTYPE html>
<?php
    session_start();
    //if (isset($_SESSION["account"])) {
    //    header("Location: home");
    //}

    if(isset($_SESSION["tester"])) {
        header("Location: login");
    }
?>
<html>
    <form style="position: absolute; left: 50%; top: 40%; translate: -50% -50%" method="post" action="index.php">
    <center><h3>KayaKraft Donator Testing</h3></center>  
    <center><input name="access-code" type="password" placeholder="Enter Access Code Here"></input></center>
    </form>
</html>
    <?php
    if(isset($_POST["access-code"])) {
        if($_POST["access-code"] == "dontateToKaya")
        header("Location: login");
        $_SESSION["tester"] = True;
    }