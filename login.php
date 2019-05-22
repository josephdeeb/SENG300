<html>
<body>

<h1>Login Page</h1>

<?php
	if(!isset($_POST["username"]) or !isset($_POST["password"])){
	  echo "<p>Please Login</p>";
	  echo '<form action="..\index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}
	$username = $_POST["username"];
	$password = $_POST["password"];

	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}

	$query = "SELECT password FROM users WHERE userName = '$username'";
	$pw = mysqli_query($con, $query);
	if(!$pw){
	  echo "<p>Please Login</p>";
	  echo '<form action="..\index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}else{
	  $row = $pw->fetch_assoc();
	  //echo "pw: ". $row['password']. " password: ". $password. "<br>";
	}

	if(strcmp($row['password'], $password) == 0){
	  //echo "login successful<br>";
	}else{
	  echo "<p>login failed </p>";
	  echo '<form action="..\index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}

	$query = "SELECT * FROM users WHERE userName = '$username'";
	$sql = mysqli_query($con,$query);
	$type = $sql->fetch_assoc()['type'];
	if($type == 1){
		echo "<h1>Submitter</h1>";
	}else if($type == 2){
		echo "<h1>Reviewer</h1>";
	}else{
		echo "<h1>Editor</h1>";
	}

	if($type == 1 or $type == 2){
	  // give submitter options
	  echo '
		<p>Submission</p>
		<form action="submit.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="submit" value="Submit Journal">
		</form>	
		<p>View Journals</p>
		<form action="viewSubs.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="submit" value="View">
		</form>
	  ';
	}
	if($type == 2){
	  // give reviewer options
	  echo '
		<p>Journals to Review</p>
		<form action="review.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="submit" value="View">
		</form>	
	  ';
	}
	if($type == 3){

	  // give editor options
	  echo '
		<form action="sumbitted.php" method="post">
		  <input type="submit" value="View Submitted Journals">
		</form>
		<form action="complete.php" method="post">
		  <input type="submit" value="View Completed Reviews">
		</form>
		<form action="view.php" method="post">
		  <input type="submit" value="View All Journals">
		</form>
		';
	}
	mysqli_close($con);
?>
<form action="..\index.php" method="post">
	<input type="submit" value="Logout">
</form>	

</body>
</html>