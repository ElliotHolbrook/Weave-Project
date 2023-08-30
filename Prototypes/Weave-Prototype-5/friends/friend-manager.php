<?php
    require_once "../libraries/accounts.php";
    session_start();

    if (!isset($_SESSION["account"])) {             //check for session
        header("Location: ../login");
    }

    $friendIds = AccountInteractions::getFriendsById($_SESSION["account"]->getId());                //get user's friends
    $incomingFriendIds = AccountInteractions::getIncomingFriendRequestsById($_SESSION["account"]->getId());     //get incoming friend requests
    $outgoingFriendIds = AccountInteractions::getOutgoingFriendRequestsById($_SESSION["account"]->getId());     //get outgoing friend requests
?>
<DOCTYPE html>
<html>
<body>
<h1>Friend Manager</h1>
<a href="../home">Back to home</a><br><br> <!-- Button to get back to the home page-->
<!-- Input form for users making a new friend request activates checkForFriend() javascript function after each time the user inputs a character
Provides immediate response to typing. Then when user submits data it is sent to sendFriendRequest() function-->
<form><input type="text" placeholder="JohnDoe#12345" id="newFriendInput" onkeyup="checkForFriend()"></input> <input id="requestButton" type="submit" value="Send Friend Request" onclick="sendFriendRequest()"></form>
<p id="friendFoundNotifier"><br></p>

<h3>Incoming Friend Requests</h3> <!--Should display any incoming friend requests while disregarding any mishandled requests-->
<ul id="incomingRequestsList">  <!-- list acts as container in which to display requests-->
    <?php 
        if(count($incomingFriendIds) === 0) {       //checks for no requests and outputs result to user as visible message
            echo "<span id=\"noIncomingSpan\" style=\"display: inline;\">You have no incoming friend requests to display</span>";
        } else {
            echo "<span id=\"noIncomingSpan\" style=\"display: none;\">You have no incoming friend requests to display</span>"; //else hides no friends message with display: none
            foreach($incomingFriendIds as $incomingFriendId) {
                $incomingFriendAccount = AccountInteractions::getAccountById($incomingFriendId);        //get account of friend so display attributes can be used
                if($incomingFriendAccount != False) {                                 //getAccountById() returns false if the ID cannot be found so it ignores these if the account hasn't been deleted properly or a value has been inputted incorrectly
                    $username = $incomingFriendAccount->getUsername();                      //get username and tag of user for display
                    $tag = $incomingFriendAccount->getTag();                    
                    //outputting profile information for viewing. ID is used for identifying the displayed user account in later javascript functions pointed to by the buttons,
                    //which send the div element as a parameter
                    echo "<li><div id=\"incoming:$incomingFriendId\">$username#$tag  <button onclick=\"acceptFriendRequest(this.parentNode);\">Add Friend</button>   <button onclick=\"ignoreFriendRequest(this.parentNode);\">Ignore</button></div></li>";
                } else {
                    //if it is decided that the account doesn't exist then it is removed from the incoming friends request list
                    AccountInteractions::removeIncomingFriendRequestFromAccountById($incomingFriendId, $_SESSION["account"]->getId());
                }
            }
        }
    ?>
</ul>

<h3>Outgoing Friend Requests</h3>
<ul id="outgoingRequestsList">
    <?php 
        if(count($outgoingFriendIds) === 0) {
            echo "<span id=\"noOutgoingSpan\" style=\"display: inline;\">You have no outgoing friend requests to display</span>";
        } else {
            echo "<span id=\"noOutgoingSpan\" style=\"display: none;\">You have no outgoing friend requests to display</span>";
            foreach($outgoingFriendIds as $outgoingFriendId) {
                $outgoingFriendAccount = AccountInteractions::getAccountById($outgoingFriendId);
                if($outgoingFriendAccount != False) {       
                    $username = $outgoingFriendAccount->getUsername();
                    $tag = $outgoingFriendAccount->getTag();
                    echo "<li><div id=\"outgoing:$outgoingFriendId\">$username#$tag  <button onclick=\"cancelFriendRequest(this.parentNode);\">Cancel</button></div></li>";
                } else {
                    AccountInteractions::removeOutgoingFriendRequestFromAccountById($incomingFriendId, $_SESSION["account"]->getId());
                }
            }
        }
    ?>
</ul>

<h3>Friends</h3>
<ul id="friendsList">
    <?php 
        if(count($friendIds) === 0) {
            echo "<span id=\"noFriendsSpan\" style=\"display: inline;\">You have no friends to display</span>";
        } else {
            echo "<span id=\"noFriendsSpan\" style=\"display: none;\">You have no friends to display</span>";
            foreach($friendIds as $friendId) {
                $friendAccount = AccountInteractions::getAccountById($friendId);
                if($friendAccount != False) {
                    $username = $friendAccount->getUsername();
                    $tag = $friendAccount->getTag();
                    echo "<li><div id=\"friend:$friendId\">$username#$tag  <button onclick=\"removeFriend(this.parentNode);\">Remove Friend</button>   <button>Block</button></div></li>";
                } else {
                    AccountInteractions::removeFriendById($friendId, $_SESSION["account"]->getId());
                }
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
        notifier = document.getElementById("friendFoundNotifier");
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
        notifier = document.getElementById("friendFoundNotifier");
        data = getFriendInputData();        //get data
        friend = data[0];           //set values
        username = data[1];
        tag = data[2];
        
        button = document.getElementById("requestButton");
        button.value = "Send Friend Request"

        if (!checkFriendInputValid(friend, tag)) {
            return;                                     //if input is invalid then function exits
        }

        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for the inputted user
        xhttp.onload = function() {
            if (this.responseText == "incomingFriend") {
                button.value = "Accept Friend Request";
                notifier.innerHTML = "This person has already sent you a friend request"
            } else {
                notifier.innerHTML = this.responseText;
            }
        }
        xhttp.open("GET", "checkForFriend.php?username=" + encodeURIComponent(username) + "&tag=" +  encodeURIComponent(tag));       
        xhttp.send();   
            }
    
    function sendFriendRequest() {
        notifier = document.getElementById("friendFoundNotifier");
        data = getFriendInputData();    //get data
        friend = data[0];               //set values
        username = data[1];
        tag = data[2];
        
        if (!checkFriendInputValid(friend, tag)) {
            return;                                     //if input is invalid then function exits
        }
        
        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            if(this.responseText) {
                notifier.innerHTML = "Friend Request Successfully Sent";
            }
        }
        xhttp.open("GET", "addFriend.php?username=" + encodeURIComponent(username) + "&tag=" +  encodeURIComponent(tag));       
        xhttp.send();   
            }
    
    function cancelFriendRequest(div) {
        divId = div.id;
        notifier = document.getElementById("friendFoundNotifier");
        id = divId.split(":")[1];
        
        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            if (this.responseText) {
                notifier.innerHTML = "Friend Request Successfully Cancelled";
                div.parentNode.remove();
                
                let numOfListElements = 0;
                document.getElementById("outgoingRequestsList").childNodes.forEach(function(currentValue, currentIndex, listObj) {
                if (currentValue.tagName == "LI") {
                    numOfListElements++;
                }
            })
            
            if(numOfListElements == 0) {
                document.getElementById("noOutgoingSpan").style.display = "inline";
            }
            }
        }
        xhttp.open("GET", "cancelFriendRequest.php?id=" + encodeURIComponent(id));       
        xhttp.send();   
    }

    function ignoreFriendRequest(div) {
        divId = div.id;
        notifier = document.getElementById("friendFoundNotifier");
        id = divId.split(":")[1];
        
        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            if (this.responseText) {
                notifier.innerHTML = "Friend Request Successfully Ignored";
                div.parentNode.remove();
                
                let numOfListElements = 0;
                document.getElementById("incomingRequestsList").childNodes.forEach(function(currentValue, currentIndex, listObj) {
                if (currentValue.tagName == "LI") {
                    numOfListElements++;
                }
            })
            
            if(numOfListElements == 0) {
                document.getElementById("noIncomingSpan").style.display = "inline";
            }
            }
        }
        xhttp.open("GET", "ignoreFriendRequest.php?id=" + encodeURIComponent(id));       
        xhttp.send();   
    }

    function acceptFriendRequest(div) {
        divId = div.id;
        id = divId.split(":")[1];
        
        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            if(this.responseText) {
                notifier.innerHTML = "Friend Request Accepted";
            }
            
            // let numOfListElements = 0;
            // document.getElementById("incomingRequestsList").childNodes.forEach(function(currentValue, currentIndex, listObj) {
            //     if (currentValue.tagName == "LI") {
            //         numOfListElements++;
            //     }
            // })
            // if(numOfListElements == 0) {
            //     document.getElementById("noOutgoingSpan").style.display = "inline";
            //     alert();
            // }
        }
        xhttp.open("GET", "addFriend.php?id=" + encodeURIComponent(id));
        xhttp.send();   
    }

    function removeFriend(div) {
        divId = div.id;
        id = divId.split(":")[1];
        notifier = document.getElementById("friendFoundNotifier");

        const xhttp = new XMLHttpRequest();             //sending the AJAX request to check for and add the inputted user
        xhttp.onload = function() {
            if(this.responseText) {
                notifier.innerHTML = "Friend Removed Successfully";
            }

            div.parentElement.remove();

            let numOfListElements = 0;
            document.getElementById("friendsList").childNodes.forEach(function(currentValue, currentIndex, listObj) {
                if (currentValue.tagName == "LI") {
                    numOfListElements++;
                }
            })
            if(numOfListElements == 0) {
                document.getElementById("noFriendsSpan").style.display = "inline";
            }
        }
        xhttp.open("GET", "removeFriend.php?id=" + encodeURIComponent(id));
        xhttp.send();   
    }
</script>
</html>