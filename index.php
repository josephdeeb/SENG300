<html>
<body>
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

<div id="button">
<form action="\SENG300\login.php" method="post">
	Login<br>
	User ID: <input type="text" name="username" required><br>
	Password: <input type="password" name="password" required><br>
	<input type="submit" value="Login">
</form>
<br><br>
<p>
   Want to have your academic journal reviewed?<br>
   Want to review others' academic journals?<br>
</p>

<form action="\SENG300\regFront.php" method="post">
   Register Here<br>
   <input type="submit" value="Register">
</form>
</div>

</body>
</html>
