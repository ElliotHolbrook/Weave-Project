//This is a function that will be run when the page loads so that messages can be fetched from the server
function getMessages() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = onXhttpStateChange(this);
    xhttp.onreadystatechange = function() {onXhttpStateChange(this);}
    xhttp.open("POST", "fetchAndOrderMessages.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

//function ran whenever there is an update to do with the request to the server to get messages
function onXhttpStateChange(xhttp) {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        alert("Log in request response recieved: " + xhttp.responseText);
    }
}
