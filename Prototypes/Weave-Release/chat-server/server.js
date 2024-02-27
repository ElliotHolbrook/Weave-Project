const http = require("http");               //native module
const socketio = require("socket.io");      //imported module
//const con = require("./dbConnect");         //js file in same dir

server = http.createServer((req, res)=>{
	res.end("Connected Successfully");          //http server to handle initial http requests
});

const io = socketio(server, {
    cors: {                                 //socket io server to handle ws connections
      origin: 'http://localhost'}  
    });

var mysql = require('mysql2');

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "kayakraft_social"
    });

    con.connect((err)=> {
    if (err) throw err;
    console.log("Connected to MySQL Database");
    });

var clients = [];                           //list holds currently connected sockets and the user's ID

function getUserBySocketId(socketId) {              //can take the ID of a socket and return the ID of the connected user
    let user = false;
    clients.forEach((value)=>{
        if(value["socketId"] == socketId) {
            user = value["userId"];
            return;                         //cancel for each loops
        }
    })
    return user;
}

function getSocketByUserId(userId) {
    let socketId = false;
    clients.forEach((client)=>{
        if(client["userId"] == userId) {
            socketId = client["socketId"];
            return;                             //cancel for each loop
        }
    })
    if(socketId !== false) {
        return io.sockets.sockets.get(socketId);        //get socket from currently open sockets
    } else {
        return false;
    }
}

function getUsernameById(id) {
    return new Promise((resolve, reject) => {
        con.query("SELECT username FROM account_data WHERE id = ?", [id], (err, result)=>{
            if(err !== null) { console.log(err) };
            if(result.length == 0) {
                reject("Invalid ID");  //user doesn't exist
            } else {
                resolve(result[0]["username"]);  //return username
            }
        })
    })
}

function checkChatIdExists(id) {
    return new Promise((resolve, reject) => {
        con.query("SELECT id FROM messages WHERE id = " + id, (err, result)=>{
            if(err !== null) { console.log(err) };
            if(result.length == 0) {
                resolve(id);  //newly generated id is okay
            } else {
                reject("ID was not Unique");  //newly generated id was not okay
            }
        })
    })
}

function generateId() {
    return new Promise((resolve, reject) => {
    partOne = Math.floor(Math.random() * (9999999999)).toString().padStart(9, "0");
    partTwo = Math.floor(Math.random() * (99999999999)).toString().padStart(10, "0");
    resolve(partOne + partTwo);
        })
}

function generateChatId() {
    function generateAndCheckIdValid() {
        return generateId().then((generatedId) => {
            //console.log("ID generated - " + generatedId);
            return checkChatIdExists(generatedId).catch((message) => {
                console.log(message);
                return generateAndCheckIdValid();            //stay recursing if id does exist
            })
        })
    }

    return generateAndCheckIdValid();
}

function saveMessageToDatabase(id, channelId, senderId, textContent, dateTime) {
    con.query("INSERT INTO messages (id, channelId, senderId, textContent, dateTimeSent) VALUES (?, ?, ?, ?, ?)", 
        [id, channelId, senderId, textContent, dateTime]);
}

io.on("connection", (sock)=>{
    console.log("New connection: " + sock.id);      //log connection in console
    
    sock.on("id", (data)=>{
        clients.push({"socketId": sock.id, "userId": data});                                   //add client to clien list
        con.query("SELECT username, tag FROM account_data WHERE id = '" + data + "'", (err, result)=> {
            if(err !== null) { console.log(err) };
            console.log(result[0]["username"] + "#" + result[0]["tag"] + " connected to the server!");  
        });
        //console.log(clients);
    });

    sock.on("disconnect", ()=>{
        console.log(sock.id + " disconnected");
        newClients = [];
        clients.forEach((value)=>{
            if(value["socketId"] !== sock.id) {
                newClients.push(value);
                clients = newClients;
            }
        })
        //console.log(clients);
    }); 

    sock.on("sendMessage", (data)=>{
        let user = getUserBySocketId(sock.id);                                                                                          //identify sender
        //console.log("Message Recieved || User: " + user + " Data: '" + data["messageText"] + "' Channel: " + data["channelId"]);        //log message recieved from sender in console
        
        getUsernameById(user).then((username) => {
        participants = [];
        //console.log("----------------------------------------");
        con.query("SELECT participants FROM channel_data WHERE id = ?", [data["channelId"]], (err, result)=> {                  //get people to send the chats to
            if(err !== null) { console.log(err); return };    
            let dateTime = new Date().getTime()
            generateChatId().then((id) => {
                saveMessageToDatabase(id, data["channelId"], user, data["messageText"], dateTime);     //save message                                                                     //check for errors
            });
            participants = result[0]["participants"];
            participantsList = JSON.parse(participants);                                    //decode participant information into usable list
            
            participantsList.forEach((value)=>{                                             
                participantSocket = getSocketByUserId(value);                               //get socket of participant
                //console.log("Attempting send to " + value);
                if(participantSocket !== false) {                                           //if socket exists then send message and log it in the console
                    // console.log("--- DUMP --- \n|user: " + typeof user + " '" + user +                  //log all information to console
                    //             "' \n|data['channelId']: " + typeof data["channelId"] + " '" + data["channelId"] + 
                    //             "' \n|data['messageText']: " + typeof data['messageText'] + " '" + data['messageText'] + 
                    //             "' \n|Participants: " + participants + "\n|Participant Socket: " + participantSocket);
                    participantSocket.emit("recieveMessage", {"senderId": user, "senderUsername": username, "channelId": data["channelId"], "textContent": data["messageText"], "dateTimeSent": dateTime})
                 } // else {
                //     console.log("Failed to send: no connection detected");                //else fail to send message
                // }
            })
        });
        });
    });
});

server.listen(8000);            //listen for http requests on port 8000
