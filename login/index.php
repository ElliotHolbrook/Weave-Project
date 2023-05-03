<html>
	<script src="themes.js" name="themes" defer></script>
	<head>
		<link rel="icon" type="image/x-icon" href="resources/kayakraft-logo-HD.png">
		
		<title>Log In</title>
	</head>
	<body onload="pageLoad()">
		
		<div class="login-1 container-1"><h1>Log In</h1></div>
		<div class="login-2 container-1">
			<form action="../CRUD/loginRequestHandler.php" method="get">
				<label for="email">Email:</label>
				<input class="inputboxlogin highlight" type="email" id="email" name="email"></input>
				<label for="password">Password:</label>
				<input class="inputboxlogin highlight" type="password" id="password" name="password"></input>
				<button type="submit">Submit</button>
				<p>or <a href="register.html">register here</a></p>
			</form>
		</div>
	</body>
</html>