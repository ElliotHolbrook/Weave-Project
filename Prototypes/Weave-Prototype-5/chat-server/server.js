const http = require("http");               //native module
const socketio = require("socket.io");      //imported module
const con = require("./dbConnect");         //js file in same dir

server = http.createServer((req, res)=>{
	res.end("Connected Successfully");
});

const io = socketio(server, {
    cors: {
      origin: 'http://localhost'}  
    });

var clients = [];

function getUserBySocketId(socketId) {
    let user;
    clients.forEach((value)=>{
        if(value["socketId"] == socketId) {
            user = value["userId"];
        }
    })
    return user;
}

io.on("connection", (socket)=>{
    console.log("New connection: " + socket.id);
    
    socket.on("id", (data)=>{
        clients.push({"socketId": socket.id, "userId": data});
        console.log(clients);
        con.query("SELECT username, tag FROM account_data WHERE id = '" + data + "'", (err, result)=> {
            if(err !== null) { console.log(err) };
            console.log(result[0]["username"] + "#" + result[0]["tag"] + " connected to the server!");  
        });
    });

    socket.on("disconnect", ()=>{
        console.log(socket.id + " disconnected");
        newClients = [];
        clients.forEach((value)=>{
            if(value["socketId"] !== socket.id) {
                newClients.push({"socketId": socket.id, "userId": value["userId"]});
            }
        })
        clients = newClients;
    });

    socket.on("sendMessage", (data)=>{
        user = getUserBySocketId(socket.id);
        console.log("Message Recieved || User: " + user + " Data: '" + data["messageText"] + "' Channel: " + data["channelId"]);
    });
});

server.listen(8000);