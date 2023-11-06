<?php
    session_start();

    
    if (isset($_SESSION["account"])) {
        header("Location: ../home");
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Weave ProtoType 4 Log In Page</title>
        </head>
        <body>
            <h1>Log In</h1><br>
            <form id="login-form" action="../controller/loginAttempt.php" method="post">
                <label for="email">Email:</label>
                <input type="text" name="email" style="

                <?php  
                    //setting the style of the email box to match the error in the url
                    if (array_key_exists("error", $_GET)){          //prevent throwing an error
                        $err = $_GET["error"];                      //get url data
                        if ($err == "invalid-email"){
                            echo "border-color: red";               //set just email box to red if email is invalid format
                        } elseif ($err == "invalid-password-email") {
                            echo "border-color: red";
                        }
                    }
                     ?>

                " id="email" required autofocus></input><br>
                <label for="password">Password:</label>
                <input type="password" name="password" style="
                
                <?php 
                    
                    if ($err == "invalid-password-email") {
                        echo "border-color: red";                   //set email and password box to red if they email correct format but password wrong
                    }

                ?>
                
                " id="password" required></input><br>
                <input type="submit">
            </form>

            No acount? Register <a href="register.php">here</a><br>


            
            
            <br>
            <br>
            <?php include_once "multicraft.php"; ?>
            <br>
            <a href="https://map.kayakraft.net/">LIVE Map</a>
            <br>





        </body>
    </html>

<?php echo "<br>Currently using PHP version: " . phpversion()?>