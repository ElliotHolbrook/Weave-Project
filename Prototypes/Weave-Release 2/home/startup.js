function setCookie(cname, cvalue) {
    document.cookie = cname + "=" + encodeURIComponent(cvalue) + ";path=/";   //no expire date set so it is deleted when page closed
  }                     //all uri encoded to deal with special characters and stuff

function getCookie(cname) {
    let name = cname + "=";
    let cookies = document.cookie;
    let ca = cookies.split(';');        //split into individual cookies
    for(let i = 0; i < ca.length; i++) {    //go through all cookies
      let c = decodeURIComponent(ca[i]);    
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {               //ignore spaces at start
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  let id = getCookie("id");
  
  //if(getCookie("id") == "") {
    const xhttp = new XMLHttpRequest();             //sending the AJAX request to for id if it's not saved as a cookie
    xhttp.onload = function() {
        if(this.responseText != false) {
            id = this.responseText;
            setCookie("id", id);            //set id as a cookie
        }
    }
    xhttp.open("GET", "getId.php");       
    xhttp.send();   
  //}

  