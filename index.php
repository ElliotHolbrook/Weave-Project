<html>
	<head>
		<link rel="stylesheet" href="resources/global-style.css"/>
		<link rel="stylesheet" href="resources/dark-theme.css" id="theme" title="dark"/>
		<link rel="icon" type="image/x-icon" href="resources/kayakraft-logo-HD.png">
		
		<title>Log In</title>
	</head>
	<body>
		
		<div class="map-button-box">
		<a href="http://kayakraft.net:8069/server/KayaKraft" class="map-button" style="padding-right: 10px;" target="_blank" rel="noopener noreferrer">Stats</a>
		<span style="padding: 18px 0px 16px 0px; border: 2px solid;"></span>
		<a href="https://map.kayakraft.net" class="map-button" style="padding-left: 16px;" target="_blank" rel="noopener noreferrer">Map</a>
		</div>
		
		
		<div class="login-1 container-1"><h1>Log In</h1></div>
		<div class="login-2 container-1">
			<form action="loginValidation.php" method="post">
				<label for="email" style="float: left; padding-top: 55px;">Email:</label>
				<input class="inputboxlogin highlight" type="email" name="email"></input>
				<label for="email" style="float: left">Password:</label>
				<input class="inputboxlogin highlight" type="password" name="email"></input>
				<center><button type="submit" style="height: 20px; margin-top: 20px">Submit</button></center>
				<p>or <a href="register.html">register here</a></p>
			</form>
		</div>
		
		<form name="themeForm">
		<label class="theme-switch">
			<input type="checkbox" name="switch" class="theme-check" id="theme-check">
			<span class="slider" onclick="switchTheme()"></span>
		</label>
		<h2 style="margin: 10px; position: relative">Dark Mode</h2>
		</form>
		
		<script name="themes">
			//Theme setup
			var defaultTheme = "light";
			var currentTheme = getCookie("theme");
			if (currentTheme == "") {
				currentTheme = defaultTheme;
			}
			if (currentTheme == "dark") {
				document.getElementById("theme-check").checked = true;
			} else {
				document.getElementById("theme-check").checked = false;
			}
			document.getElementById("theme").href = "resources/" + currentTheme + "-theme.css";
			
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
		</script>
		
		
		<script name="input validation">
			//Inputs are also validated on the server side so don't bother messing with these.
				
		</script>
		<?php require_once 'betawarning.php';?>
	</body>
</html>