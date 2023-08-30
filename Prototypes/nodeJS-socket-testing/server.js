const http = require("http");
const websocket = require("ws");

const server = http.createServer((req, res)=>{
	res.end("Connected Successfully");
});

const wss = new websocket.WebSocketServer({server});

wss.on("headers", (headers, req)=>{
	console.log("New Client Connected");
	console.log(headers);
});

wss.on("connection", (ws, req)=>{
	ws.on("message", (data, isBin)=>{
		console.log("Message Sent: " + data.toString());
		wss.clients.forEach(element => {
			element.send(data.toString());
		});
	})
});


server.listen(8000);