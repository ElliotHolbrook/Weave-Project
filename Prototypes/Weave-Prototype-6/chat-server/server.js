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

function checkForChatId(id) {
    con.query("SELECT id FROM account_data WHERE id = " + id, (err, result)=>{
        if(err !== null) { console.log(err) };
        if(result.length != 0) {
            exists = true;
        }
        console.log("result.length: " + result.length);
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
    //return "1";
        })
}

function generateChatId() {
    // function generateAndCheckIdValid() {
    //     return generateId().then((generatedId) => {
    //         console.log("ID generated - " + generatedId);
    //         return checkChatIdExists(generatedId).then((validId) => {
    //         //     console.log("ID approved - " + validId);
    //              return validId;
    //          })
    //         }).catch((message) => {
    //             console.log(message);
    //             return generateAndCheckIdValid();            //stay in the loop if id does exist
    //         })
    // }

    // return generateAndCheckIdValid();
    
    async function generateAndCheckIdValid() {
        let id = await generateId();
        console.log("ID generated - " + id);
        try {
          const validId = await checkChatIdExists(id);
          return validId;
          // If you prefer, the above 2 lines can be shortened to:
          // return await checkChatIdExists(id);
        } catch (message) {
          console.log(message);
          return generateAndCheckIdValid()
        }
      }

    return generateAndCheckIdValid();

    //do {
        // partOne = Math.floor(Math.random() * (9999999999)).toString().padStart(9, "0");
        // partTwo = Math.floor(Math.random() * (99999999999)).toString().padStart(10, "0");
        // id = partOne + partTwo;
        // id = "1";
        
        
        // let isUnique = new Promise((resolve, reject) => {
        //     con.query("SELECT id FROM messages WHERE id = " + id, (err, result)=>{
        //         if(err !== null) { console.log(err) };
        //         console.log(idExists);
        //         if(result.length == 0) {
        //             resolve("ID was Unique");  //newly generated id is okay
        //         } else {
        //             reject("ID was not Unique");  //newly generated id was not okay
        //         }
        //         // console.log("result.length: " + result.length);
        //         // console.log("exists inside function: " + idExists)
        //     })
        // })
        
        // con.query("SELECT id FROM messages WHERE id = " + id, (err, result)=>{
        //     if(err !== null) { console.log(err) };
        //     console.log(idExists);
        //     if(result.length != 0) {
        //         idExists = true;
        //     }
        //     console.log("result.length: " + result.length);
        //     console.log("exists inside function: " + idExists)
        // })

        // let existingChats = await con.query("SELECT id FROM messages WHERE id = " + id);
        // console.log(existingChats);
        // if(existingChats.length != 0) {
        //     idExists = true;
        // }
        // console.log("result.length: " + result.length);
        // console.log("exists inside function: " + idExists)
        
        // isUnique.then((message) => {
        //     console.log(message);
        //     idExists = false;
        // }).catch((message) => {
        //     console.log(message);
        //     idExists = true;
        // })

        // console.log("^-^");
        // console.log("exists outside function: " + idExists);
        // console.log(toString(Math.floor(Math.random() * (9999999999))));
        //console.log("hello")

        //console.log(generateAndCheckIdValid());



    //} while (idExists);



    //return id;
}

function saveMessageToDatabase(id, channelId, senderId, textContent) {
    con.query("INSERT INTO messages (id, channelId, senderId, textContent) VALUES ('" + id + "','" + channelId + "','" + senderId + "','" + textContent + "')")
}

function getChannelParticipants(channelId) {
    let participants = [];
    con.query("SELECT participants FROM channel_data WHERE id = '" + channelId + "'", (err, result)=> {
        if(err !== null) { console.log(err) };
        console.log(participants);
        participants = result[0]["participants"];
        console.log(participants);
    });
    return participants;
}

io.on("connection", (sock)=>{
    console.log("New connection: " + sock.id);      //log connection in console
    
    sock.on("id", (data)=>{
        clients.push({"socketId": sock.id, "userId": data});                                   //add client to clien list
        con.query("SELECT username, tag FROM account_data WHERE id = '" + data + "'", (err, result)=> {
            if(err !== null) { console.log(err) };
            console.log(result[0]["username"] + "#" + result[0]["tag"] + " connected to the server!");  
        });
        console.log(clients);
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
        console.log(clients);
    }); 

    sock.on("sendMessage", (data)=>{
        let user = getUserBySocketId(sock.id);                                                                                          //identify sender
        console.log("Message Recieved || User: " + user + " Data: '" + data["messageText"] + "' Channel: " + data["channelId"]);        //log message recieved from sender in console
        
        participants = [];
        console.log("----------------------------------------");
        con.query("SELECT participants FROM channel_data WHERE id = ?", [data["channelId"]], (err, result)=> {                  //get people to send the chats to
            if(err !== null) { console.log(err); return };    
            saveMessageToDatabase(generateChatId(), data["channelId"], user, data["messageText"]);     //save message                                                                     //check for errors
            participants = result[0]["participants"];
            participantsList = JSON.parse(participants);                                    //decode participant information into usable list
            
            participantsList.forEach((value)=>{                                             
                participantSocket = getSocketByUserId(value);                               //get socket of participant
                console.log("Attempting send to " + value);
                if(participantSocket !== false) {                                           //if socket exists then send message and log it in the console
                    console.log("--- DUMP --- \n|user: " + typeof user + " '" + user +                  //log all information to console
                                "' \n|data['channelId']: " + typeof data["channelId"] + " '" + data["channelId"] + 
                                "' \n|data['messageText']: " + typeof data['messageText'] + " '" + data['messageText'] + 
                                "' \n|Participants: " + participants + "\n|Participant Socket: " + participantSocket);
                    participantSocket.emit("recieveMessage", {"senderId": user, "channelId": data["channelId"], "message": data["messageText"]})
                } else {
                    console.log("Failed to send: no connection detected");                //else fail to send message
                }
            })
        });
    });
});

server.listen(8000);            //listen for http requests on port 8000