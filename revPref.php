<!--

submit.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page gives users the ability to choose their journal file from their filesystem.
This page also supplies 3 dropdown menus for preferred and non-preferrend reviewers for the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu

-->
<html>
<head>
<title>Register</title>
<link href="stylesubmit.css" type="text/css" rel="stylesheet" />
</head>
<body>
<head>
<div class="rectangle"></div>
<h1>Submit Journal</h1>
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
	
		// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");
	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify
	
	

	$query = "SELECT * FROM users WHERE type=1 OR type=2";
	$result = mysqli_query($con,$query);
	
	while($row = mysqli_fetch_array($result)){
		echo '					<br>
			<div class="list">
			<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>
			</div>			';
	}
	echo ' </select>
		   <br>
				<div class="preferred">
				Your Preferred Reviewer 1: <select name="pref1">
				</div>

										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				<div class="preferred2">
				Your Preferred Reviewer 2: <select name="pref2">
				</div>
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				<div class="preferred3">
				Your Preferred Reviewer 3: <select name="pref3">
				</div>
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}

	echo '	<input type="hidden" name="username" value ='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<div class="uploadJournal">
				<input type="submit" value="Submit Preferences">
			</form>
			
		 ';
?>

<div id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
    <input type="hidden" name="lgdin" value=1>
	<div class="mainMenu">
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div id="button">
<form action="../index.php" method="post">
	<div class="logoutButton">
	<input type="submit" value="Logout">
	</div>
</form>	
</div>

</body>
</html>
