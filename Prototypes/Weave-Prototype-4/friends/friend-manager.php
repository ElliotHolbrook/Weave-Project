<?php
    require_once "../libraries/accounts.php";
    session_start();

    if (!isset($_SESSION["account"])) {
        header("Location: ../login");
    }

    $friendIds = AccountInteractions::getFriendsById($_SESSION["account"]->getId());
?>
<DOCTYPE html>
<html>
<body>
<h1>Friend Manager</h1>
<a href="../home">Back to home</a><br><br>
<form><input type="text" placeholder="JohnDoe#12345" id="newFriendInput" onkeyup="checkForFriend()"></input> <input type="submit" value="Add Friend" onclick="addFriend()"></form>
<p id="friendFoundNotifier"><br></p>
<ul id="friendsList">
    <?php 
        if(count($friendIds) === 0) {
            echo "You have no friends to display";
        } else {
            foreach($friendIds as $friendId) {
                $friendAccount = AccountInteractions::getAccountById($friendId);
                $username = $friendAccount->getUsername();
                $tag = $friendAccount->getTag();
                echo "<li><div>$username#$tag  <button>Remove Friend</button>   <button>Block</button></div></li>";
            }
        }
    ?>
</ul>
</body>
<script>
    function getFriendInputData() {
        inputBox = document.getElementById("newFriendInput");   //getting inputted value of friend's name
        friend = inputBox.value;  
        
        notifier = document.getElementById("friendFoundNotifier");
        
        username = friend.split("#")[0];            //splitting username into parts 
        tag = friend.split("#")[1];

        return [friend, username, tag];
    }
    
    function checkFriendInputValid(friend, tag) {
        if (!friend.includes("#")) {            //validating that it could be a valid input before sending the ajax request
            notifier.innerHTML = "<br>";            //resetting notifier if name is changed or invalidateed
            return false;
        }

        if (tag.length != 5) {
            notifier.innerHTML = "<br>";             //resetting notifier if name is changed or invalidateed
            return false;                             //returning that value is invalid
        }

        return true;
    }
    
    // the following function will allow the user to see if the usernmame and tag that they inputted is a real account when they have finished typing
    //If this uses too much compute power then it should be abandoned and the database should only be queried on press of add friend button
    function checkForFriend() {
        data = getFriendInputData();        //get data
        friend = data[0];           //set values
        username = data[1];
        tag = data[2];
        
        if (!checkFriendInputValid(friend, tag)) {
            return;                                     //if input is invalid then function exits
        }

        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for the inputted user
        xhttp.onload = function() {
            notifier.innerHTML = this.responseText;
        }
        xhttp.open("GET", "checkForFriend.php?username="+username+"&tag="+tag);       
        xhttp.send();   
            }
    
    function addFriend() {
        data = getFriendInputData();    //get data
        friend = data[0];               //set values
        username = data[1];
        tag = data[2];
        
        if (!checkFriendInputValid(friend, tag)) {
            return;                                     //if input is invalid then function exits
        }
        
        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            alert(this.responseText);
        }
        xhttp.open("GET", "addFriend.php?username="+username+"&tag="+tag);       
        xhttp.send();   
            }
</script>
</html>