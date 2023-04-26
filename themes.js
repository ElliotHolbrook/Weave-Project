//Declaring globals
var defaultTheme
var currentTheme

//Theme setup
function pageLoad() {
	themePrep()
	alert("pageLoad ran")
}

//function themePrep() {	
	var defaultTheme = "light";					//default theme
	var currentTheme = getCookie("theme");		//gets saved cookie theme
	if (currentTheme == "") {
		currentTheme = defaultTheme;
	}
	if (currentTheme == "dark") {				//setting the slider to be right
		document.getElementById("theme-check").checked = true;
	} else {
		document.getElementById("theme-check").checked = false;
	}
	document.getElementById("theme").href = "resources/" + currentTheme + "-theme.css";
	alert("themePrep ran")
//}

function switchTheme() {
	if (document.getElementById("theme-check").checked) {
		currentTheme = "light";
		document.getElementById("theme").href = "resources/light-theme.css";
		document.cookie = "theme=light"
	} else {
		currentTheme = "dark";
		document.getElementById("theme").href = "resources/dark-theme.css";
		document.cookie = "theme=dark"
	}
	alert("switchTheme ran")
}

function getCookie(cname) {
	let name = cname + "=";
	let ca = document.cookie.split(';');
	for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}