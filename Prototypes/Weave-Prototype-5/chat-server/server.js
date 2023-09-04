const http = require("http");               //native module
const socketio = require("socket.io");      //imported module
//const con = require("./dbConnect");         //js file in same dir
const { channel } = require("diagnostics_channel");     //idk what this is

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
        return io.sockets.sockets.get(socketId);
    } else {
        return false;
    }
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
    console.log("New connection: " + sock.id);
    
    sock.on("id", (data)=>{
        clients.push({"socketId": sock.id, "userId": data});
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
                newClients.push({"socketId": sock.id, "userId": value["userId"]});
            }
        })
        clients = newClients;
    }); 

    sock.on("sendMessage", (data)=>{
        let user = getUserBySocketId(sock.id);
        console.log("Message Recieved || User: " + user + " Data: '" + data["messageText"] + "' Channel: " + data["channelId"]);
        participants = [];
        console.log("----------------------------------------");
        con.query("SELECT participants FROM channel_data WHERE id = ?", [data["channelId"]], (err, result)=> {
            if(err !== null) { console.log(err) };
            //console.log(typeof data["channelId"] + data["channelId"]);
            //console.log(result);
            participants = result[0]["participants"];
            //console.log(participants);
            participantsList = JSON.parse(participants);

            participantsList.forEach((value)=>{
                participantSocket = getSocketByUserId(value);
                console.log(value);
                if(participantSocket !== false) {
                    participantSocket.emit("recieveMessage", {"senderId": user, "channelId": data["channelId"], "message": data["messageText"]})
                }
            })
        });
    });
});

server.listen(8000);            //listen for http requests on port 8000