<html>
<body>
<?php
	
	if(empty($_POST["username"]) or empty($_POST["password"]) or empty($_POST["password2"]) or empty($_POST["fname"]) or empty($_POST["lname"])){
		
	}
	require_once("DBController.php");
	$db_handle = new DBController();
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	$password2 = $_POST["password2"];
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$type = $_POST["type"];

	// Create connection
	$con=mysqli_connect("localhost","seng300","seng300Spr2019","seng300");

	// Check connection
	if (mysqli_connect_errno($con))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	if(strcmp($password, $password2) !== 0){
		echo "passwords are different";
		echo '<form action="regFront.php" method="post">
				<input type="submit" value="Return to Registration Page">
			  </form>	
			 ';
		echo '<form action="index.php" method="post">
				<input type="submit" value="Return to Login Page">
			  </form>	
			 ';
		die();
	}

	$sql = "INSERT INTO users VALUES ('". $username."','".$password."','".$fname."','".$lname."','".$type."')";
	if (!mysqli_query($con,$sql)){
		echo	'<p>Error Registering. Please try again.</p>';
		echo	'<form action="regFront.php" method="post">
					<input type="submit" value="Try Again">
				</form>';
	}else{
		echo '<p>You have registered Successfully.</p>';
	}	  
mysqli_close($con);
?>

<form action="..\index.php" method="post">
	<input type="submit" value="Return to Main Menu">
</form>	

</body>
</html>