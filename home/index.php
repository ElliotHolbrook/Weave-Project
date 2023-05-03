<html>
	<head>
		<link rel="stylesheet" href="../resources/global-style.css"/>
		<link rel="stylesheet" href="../resources/dark-theme.css" id="theme" title="dark"/>
		<link rel="icon" type="image/x-icon" href="resources/kayakraft-logo-HD.png">
		
		<title>Home</title>
	</head>
	<body class="grid-container homepage-grid" style="display: grid; grid-template-columns: 60px 250px auto 1000px;background-color: rgb(64, 64, 64); padding: 10px; column-gap: 5px; row-gap: 2px; margin-bottom: 20px;">
		<div style="grid-column: 1; grid-row: 1 / 3 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 20px; font-size: 30px; text-align: center; margin-bottom: 20px;">
		</div>
		<div style="display: grid; grid-template-rows: 250px auto auto ; grid-column: 2; grid-row: 1 / 3 ; background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 10px; font-size: 30px; text-align: center; margin-bottom: 20px;">
			<div style="grid-column: 1; grid-row: 1 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 2px; font-size: 30px; text-align: center;">
			</div>
		</div>
		<div style="display: grid; grid-template-rows: 50px auto auto 50px ; grid-column: 3 / 5; grid-row: 1 / 3;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 20px; font-size: 30px; text-align: center; margin-bottom: 20px;">
			<div style="grid-column: 1; grid-row: 1 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 2px; font-size: 30px; text-align: center;width: 100%">
			</div>
			<div style="grid-column: 1; grid-row: 2 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 2px; font-size: 30px; text-align: center;width: 100%">
			</div>
			<div style="grid-column: 1; grid-row: 3 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 2px; font-size: 30px; text-align: center;width: 100%">
			</div>
			<form action="<?php basename(__FILE__, '.php'); ?>" style="display: grid; grid; grid-template-columns: 80% auto ;grid-column: 1; grid-row: 4 ;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 2px; font-size: 30px; text-align: center;width: 100%; height: 100%;">
				<input name="message" style="width: 100%; height: 100%; font-size: 20px; grid-column: 1;"></input>
				<input type="submit" style="width: 100%; height: 100%; grid-column: 2;"></input>
			</form>
		</div>
		<div style="grid-column: 5; grid-row: 1 / 3;background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 20px; font-size: 30px; text-align: center; margin-bottom: 20px;">
		</div>
	<body>
	<script>
		function sendMessage()	
			message.open("GET", "../CRUD/sendMessage.php");
			message.send();
	</script>
</html>