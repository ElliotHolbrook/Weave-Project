<?php
    require_once "../libraries/accounts.php";
    session_start();
    if (!isset($_SESSION["account"])) {
        header("Location: ../login");
    }

    if(!isset($_SESSION["tester"])) {
        header("Location: ../index.php");
    }
?>
<!DOCTYPE html>
<html>
    <h1>Home</h1><br>
    <!--<form action="" method="post">
        <label for="message-input">Type your message here: </label>
        <input type="text" id="message-input" name="message-input" autocomplete="off"></input> <br>
        <input type="submit"></input>
    </form>-->
	<h3>Temp Chat</h3>
	<div><ul id="messages" style="overflow-y: scroll; max-height: 300px; width: 500px; overflow-x: hidden; word-wrap: break-word; maxlength: 4000"></ul>
		<label for="messageBox">Message:</label>
		<input id="messageBox" type="text" autocomplete="off"></input>
		<input id="sendButton" type="button" onclick="sendMessage()" value="Send"></input>
	</div>
    <br>
    
    Username: <?php    echo $_SESSION["account"]->getUsername() . "#" . $_SESSION["account"]->getTag(); ?><br>

    <a href="../friends/friend-manager.php">Manage Friends</a><br><br>
    
    <form action="../controller/logOut.php" method="get">
        <button type="submit">Log Out</button>
    </form>
	<script src="https://cdn.socket.io/4.6.0/socket.io.min.js" 
			integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" 
			crossorigin="anonymous">
	</script>
	<script>
		// Get the input field
		var input = document.getElementById("messageBox");

		// Execute a function when the user presses a key on the keyboard
		input.addEventListener("keypress", function(event) {
		  // If the user presses the "Enter" key on the keyboard
		  if (event.key === "Enter") {
			// Cancel the default action, if needed
			event.preventDefault();
			// Trigger the button element with a click
			document.getElementById("sendButton").click();
		  }
		});
		
		
		
		console.log(io);
		const socket = io("http://localhost:8000/");
		console.log(socket);

		socket.on("message", (data)=>{
			console.log(data);
		});
		
		socket.on("connect", ()=>{
			console.log(socket.id);
		});
		
		
		messageBox = document.getElementById("messageBox");
		messageContainer = document.getElementById("messages");
		
		function showMessage(data) {
			doScroll = false;
			if(Math.abs(messageContainer.scrollHeight - messages.clientHeight - messages.scrollTop) < 20) {
				doScroll = true;
			}
			newMessageLi = document.createElement("Li");
			newMessageLi.innerHTML = data;
			messageContainer.appendChild(newMessageLi);
			if(doScroll){
				messages.scrollTop = messageContainer.scrollHeight - messages.clientHeight;
			}
		}

		socket.on("recieveMessage", (data)=>{
			console.log(data);
			showMessage(data);
		});

		function sendMessage() {
			message = messageBox.value;
			if (message == "") {return};
			messages.scrollTop = messageContainer.scrollHeight - messages.clientHeight;
			socket.emit("sendMessage", message);
			messageBox.value = "";
		}
	</script>
</html>