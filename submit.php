<!--

submit.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page gives users the ability to choose their journal file from their filesystem.
This page also supplies 3 dropdown menus for preferred and non-preferrend reviewers for the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu

--->
<html>
<body>
<head>
<h1>Submit Journal Page</h1>
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
	
	
	// submit journal
	echo '  <form action="upload.php" method="post" enctype="multipart/form-data">
				Select Journal to upload:
				<input type="file" name="fileToUpload" id="fileToUpload" required	><br>
				Preferred Reviewer 1: <select name="pref1">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Preferred Reviewer 2: <select name="pref2">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Preferred Reviewer 3: <select name="pref3">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Non-Preferred Reviewer 1: <select name="npref1">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Non-Preferred Reviewer 2: <select name="npref2">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Non-Preferred Reviewer 3: <select name="npref3">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>';

	echo '	<input type="hidden" name="username" value ='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="submit" value="Upload Journal">
			</form>
		 ';

?>

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