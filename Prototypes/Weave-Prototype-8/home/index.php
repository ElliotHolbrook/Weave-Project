<?php
    require_once "../libraries/accounts.php";
    require_once "../libraries/messages.php";
    session_start();
    
    if (!isset($_SESSION["account"])) {
        header("Location: ../login");
    }

    $dms = ChatInteractions::getDMsById($_SESSION["account"]->getId());
?>
<!DOCTYPE html>
<html>
    <h1>Home</h1><br>
    <h3>Chats</h3>
    <select name="friend" id="friend" oninput="changeChatRecipient(this)">
        <?php
            foreach($dms as $dm) {
                $friendAccount = AccountInteractions::getAccountById($dm["buddyId"]);
                if($friendAccount != False) {
                    $username = $friendAccount->getUsername();
                    $tag = $friendAccount->getTag();
                    echo "<option data-channelId='" . $dm["channelId"] . "'>$username#$tag</option>";
                } // else {
				//     AccountInteractions::removeFriendById($friendId, $_SESSION["account"]->getId());
				// }
            }
        ?>
    </select>
	<div><ul id="messages" style="background-color: rgb(230, 230, 230); overflow-y: scroll; max-height: 300px; min-height: 300px; width: 500px; overflow-x: hidden; word-wrap: break-word; maxlength: 4000">
		</ul>
		<label for="messageBox">Message:</label>
		<input id="messageBox" type="text" autocomplete="off"></input>
		<input id="sendButton" type="button" onclick="sendMessage()" value="Send"></input>
	</div>

	<!-- CHAT TEMPLATE -->

	<li id="chatTemplate" style="display: none; margin-bottom: 5px;"><div>
				<span data-display="username"></span>  <span data-display="dateTime" style="font-size: 10px; color: gray"></span><br>
				<span data-display="text"></span>
			</div></li>

	<!-- END OF CHAT TEMPLATE -->
    <br>
    
    Username: <?php    echo $_SESSION["account"]->getUsername() . "#" . $_SESSION["account"]->getTag(); ?><br>

    <a href="../friends/friend-manager.php">Manage Friends</a><br><br>
    
    <form action="../controller/logOut.php" method="get">
        <button type="submit">Log Out</button>
    </form>

    <script src="startup.js"></script>
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" 
			integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" 
			crossorigin="anonymous">
	</script>
	<script>
		// Get the input field
		var input = document.getElementById("messageBox");

		//enter button chat send
		input.addEventListener("keypress", function(event) {
		  if (event.key === "Enter") {
			// Cancel the default submit action
			event.preventDefault();
			// Trigger the button element
			document.getElementById("sendButton").click();
		  }
		});
		
		
		
		console.log(io);
		const socket = io("http://localhost:8000/");
		console.log(socket);
		


		socket.on("connect", ()=>{
			socket.emit("id", getCookie("id"));
		});
		
		
		messageBox = document.getElementById("messageBox");
		messageContainer = document.getElementById("messages");
		
		function showMessage(data) {
			return new Promise((resolve, reject) => {
				formatMessage(data).then((newMessageLi) => {
					messageContainer.appendChild(newMessageLi);
					resolve();
				})
			})
		}

		function formatMessage(data) {
			return new Promise((resolve, reject) => {
				newMessageLi = document.getElementById("chatTemplate").cloneNode(true);				//clone template
				newMessageLi.style.display = "";													//set attributes
				newMessageLi.id = "";
				newMessageLi.setAttribute("data-senderId", data["senderId"]);
				newMessageLi.setAttribute("data-dateTime", data["dateTimeSent"]);
				
				let displayUsername = newMessageLi.querySelector("[data-display='username']");		//get data locations
				let displayDateTime = newMessageLi.querySelector("[data-display='dateTime']");
				let displayText = newMessageLi.querySelector("[data-display='text']");

				let dateTime = new Date(data["dateTimeSent"]);										//turn datetime into date object

				displayUsername.innerHTML = "<b>" + data["senderUsername"] + "</b>";						//insert data
				displayDateTime.innerHTML = dateTime.getDate().toString().padStart(2, "0") + "/" + (dateTime.getMonth() + 1).toString().padStart(2, "0") + "/" + dateTime.getFullYear().toString() + " " + dateTime.getHours().toString().padStart(2, "0") + ":" + dateTime.getMinutes().toString().padStart(2, "0");
				displayText.innerHTML = data["textContent"];

				resolve(newMessageLi);
			});
		}

		socket.on("recieveMessage", (data)=>{
			console.log("Message Recieved");
			console.log(data);
			if(data["channelId"] == channelId) {
				doScroll = false; //don't scroll to see new message by default
				if(Math.abs(messageContainer.scrollHeight - messages.clientHeight - messages.scrollTop) < 20) { 
					doScroll = true;        //if scrolled within 20px of the bottom of the box then scroll after
				}
				showMessage(data).then(() => {
					if(doScroll){
						messages.scrollTop = messageContainer.scrollHeight - messages.clientHeight; //scroll to new bottom of the box
					}
				})
			} else {
				let newMessage = document.createElement("Li");
				newMessage.innerHTML = data["textContent"];
				if(savedMessages[data["channelId"]] !== undefined) {
					console.log(savedMessages[data["channelId"]]);
					savedMessages[data["channelId"]].push(newMessage);
				} else {
					savedMessages[data["channelId"]] = [newMessage];
				}
			}
		});

		function sendMessage() {
			message = messageBox.value;
			if (message == "") {return};
			messages.scrollTop = messageContainer.scrollHeight - messages.clientHeight;
			console.log({"messageText": message, "channelId": channelId.toString()});
			socket.emit("sendMessage", {"messageText": message, "channelId": channelId.toString()});
			messageBox.value = "";
		}

		function getMessagesFromDB(channelId, startIndex, endIndex) {
			return new Promise((resolve, reject) => {
				let messages = {};
				const xhttp = new XMLHttpRequest();             //sending the AJAX request to get messages from the server
				xhttp.onload = function() {
					let messages = [];
					console.log("Recieved Data:");
					let data = JSON.parse(this.responseText);		//recieve previous chats
					console.log(data);
					Object.keys(data).forEach((key) => {			//go through all the chats
						let value = data[key];
						value["dateTimeSent"] = parseInt(value["dateTimeSent"]);
						// if(data[key - 1] != undefined) {
						// 	let lastMessage = data[key - 1];
						// 	if((lastMessage["senderId"] == value["senderId"]) && ((lastMessage["dateTimeSent"] - value["dateTimeSent"]) < 600000)) {
						// 		console.log("Passes time check");
						// 		newMessage = document.createElement("Li");
						// 		newMessage.innerHTML = value["textContent"];
						// 		messages.push(newMessage);
						// 	} else {
						// 		formatMessage(value).then((newMessage) => {
						// 			messages.push(newMessage);
						// 		})
						// 	}
						// } else {
							formatMessage(value).then((newMessage) => {
								messages.push(newMessage);
						 	})
						// }
						
					}); 
					console.log("Data");
					console.log(messages);
					
					resolve(messages);
				}
				xhttp.open("GET", "fetchMessages.php?channelId=" + encodeURIComponent(channelId) + "&startIndex=" +  encodeURIComponent(startIndex) + "&endIndex=" +  encodeURIComponent(endIndex));       
				xhttp.send();  
			})
		}

        var channelId = "<?php echo $dms[0]["channelId"] ?>";			//default to 1st DM channel
		savedMessages = {};												//set saved messages to {} on page load
        function changeChatRecipient(selecter) {						//run when the selecter switches to a new channel
            savedMessages[channelId] = [...messageContainer.children];								//save all currently displayed messages to the location of the current channel ID 
			
			channelId = selecter.options[selecter.selectedIndex].getAttribute('data-channelId');	//get the channel ID that is to be switched to
			
			messageContainer.innerHTML = "";									//delete old messages
			
			let displayMessages = new Promise((resolve, reject) => {
			if((savedMessages[channelId] == undefined) || (savedMessages[channelId].length == 0)) {							//if no messages saved then get 30 most recent messages
				getMessagesFromDB(channelId, 1, 60).then((messages) => {
					savedMessages[channelId] = messages;
					console.log(savedMessages);
					console.log("Displaying messages");
					savedMessages[channelId].forEach((message)=>{			//display messages
						messageContainer.append(message);
					});
					resolve();
				})
			} else {
				console.log("Displaying messages");
				savedMessages[channelId].forEach((message)=>{		//display messages
					messageContainer.append(message);
				});
				resolve();
			}
			})
			
			displayMessages.then(() => {
				messages.scrollTop = messageContainer.scrollHeight - messages.clientHeight;
				console.log("scrolling");
			})
		}

		changeChatRecipient(document.getElementById("friend"));			//update to show chats when page loads

		// var loadPending = true;
		// messages.onscroll = () => {
		// 	if(loadPending) {
		// 		return;
		// 	}
			
		// 	loadPending = true;
		// 	console.log(messages.scrollTop);
		// }
	</script>
</html>
</html>