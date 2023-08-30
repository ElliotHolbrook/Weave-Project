//const express = require("express");
//const app = express();
const http = require("http");
//const { disconnect } = require("process");
const socketio = require("socket.io");

//app.use(express.static(__dirname + "/public"));
server = http.createServer((req, res)=>{
	res.end("Connected Successfully");
});

//const expressServer = app.listen(8000);
//const io = socketio(expressServer);
const io = socketio(server, {
    cors: {
      origin: 'http://localhost'}  
    });

io.on("connection", (socket)=>{
    console.log(socket.id + " connected");
    //console.log(socket);
    socket.emit("message", "Hello!")

    socket.on("disconnect", ()=>{
        console.log(socket.id + " disconnected");
    });

    socket.on("sendMessage", (data)=>{
        io.sockets.emit("recieveMessage", data);
    });
});

server.listen(8000);