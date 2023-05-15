//THIS IS FOR COMMUNICATING WITH THE LOGIN HANDLER THROUGH AJAX - IT IS WORKING
/*
//function ran when somebody tries to log in
function logInAttempt() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = onXhttpStateChange(this);
    xhttp.onreadystatechange = function() {onXhttpStateChange(this);}
    xhttp.open("POST", "CRUD/logInHandler.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(getFormData());
}

//function ran whenever there is an update to do with the request to the server to log in
function onXhttpStateChange(xhttp) {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        alert("Log in request response recieved: " + xhttp.responseText);
    }
}

function getFormData() {
    var form = document.getElementById("logInForm");
    var data = new FormData(form);
    var parameters = new URLSearchParams(data);
    return parameters.toString();
}
*/