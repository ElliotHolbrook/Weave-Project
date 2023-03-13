<html>
	<head>
		<link rel="stylesheet" href="resources/global-style.css"/>
		<link rel="icon" type="image/x-icon" href="resources/kayakraft-logo-HD.png">
		
		<title>Log In</title>
	</head>
	<body>
		<div class="map-button-box">
		<a href="http://kayakraft.net:8069/server/KayaKraft" class="map-button" style="padding-right: 10px;" target="_blank" rel="noopener noreferrer">Stats</a>
		<span style="padding: 18px 0px 16px 0px; border: 2px solid;"></span>
		<a href="https://map.kayakraft.net" class="map-button" style="padding-left: 16px;" target="_blank" rel="noopener noreferrer">Map</a>
		
		</div>
		
		
		<div class="login-1 container-1 highlight"><h1>Log In</h1></div>
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
	</body>
</html>