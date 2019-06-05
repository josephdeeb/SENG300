<html>
<head>
<title>Journal Review</title>
<link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<h1>Software Engineering Journal Review</h1>
<br><br>

<?php

if(!empty($_POST["registered"])){
	echo "<p>You have successfully registered!</p><br>
	      <p>Welcome to the Software Engineering Journal</p>";
}else if(!empty($_POST["regErr"])){
	echo "<p>There was an error with your registration.</p><br>
	      <p>Please try again.</p>";
}
?>

<form action="\SENG300\login.php" method="post">
	<!--Login<br>-->
	<div class="username">
	User ID: <input type="text" name="username" required><br>
	</div>
	<div class="password">
	Password: <input type="password" name="password" required><br>
	</div>
	<div class="loginButton">
	<input type="submit" value="Login">
	</div>
</form>
<br><br>
	<div class="askMessage">
	<p>Want to have your academic journal reviewed?<br> Want to review others' academic journals?<br> </p>
	<hr width="300px"/>
	</div>
	<div class="register">
	<form action="\SENG300\regFront.php" method="post">
		<b>Register Here</b><br>
	</div>
	<div class="registerButton">
		<input type="submit" value="Register">
	</div>
	</form>
</div>
</body>
</html>
