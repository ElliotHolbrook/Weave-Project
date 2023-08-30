const express = require("express");
const app = express();
const socketio = require("socket.io");

app.use(express.static(__dirname + "/public"));

const expressServer = app.listen(8000);
const io = socketio(expressServer);

io.on("connection", (socket)=>{
    console.log(socket.id + " connected");
    //console.log(socket);
    socket.emit("message", "Hello!")

    socket.on("disconnect", ()=>{
        console.log(socket.id + " disconnected");
    });
});

io.on("sendMessage", (data)=>{
    console.log(io.sockets.clients);
});

//server.listen(8000);