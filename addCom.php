<html>
<body>

<?php
	if(!isset($_POST["username"]) or !isset($_POST["lgdin"])){
	  echo "<p>Please Login</p>";
	  echo '<form action="index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}
	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	$fname = $_POST["fname"];
	$comment = $_POST["comment"];
	
	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}

	echo '<h1>Add Comments to '. $fname. '</h1>';

	// add comment to db
	$query = "INSERT INTO comments VALUES ('$fname','$username','$comment')";
	if(mysqli_query($con,$query)){
		echo "<p>Comment Added Successfully.</p>";
	}else{
		echo "<p>Error during adding comment. Please try again.</p>";
	}

	// end verify
	mysqli_close($con);
?>

<div id="button">
<form action="addCom.php" method="post">
    <input type="text" name="comment">
	<input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="hidden" name="fname" value="<?php echo $fname; ?>">
	<input type="submit" value="Add Comment to Journal">
</form>
</div>
<div id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div id="button">
<form action="../index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>

</body>
</html>