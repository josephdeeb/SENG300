<!--

assignReviewers.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This is the page where the editor assigns reviewers to journals (selected from viewUnassignedJournals)

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
    fname     	- Filename of the journal being assigned reviewers
	rev(1-3)	- Reviewers selected to be assigned

---> 
<html>
<body>

<?php
    // Make sure the user is logged in
	if(!isset($_POST["lgdin"]) or !isset($_POST["username"])){
	  echo "<p>Please Login</p>";
	  echo '<form action="index.php" method="post">
			  <input type="submit" value="Return to Login Page">
			</form>	
	  ';
	  die();
	}
	
	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify
	
	$username = $_POST["username"];
	$fname = $_POST["fname"];
	
	//
	//
	// If submitted hasn't been posted, its the users first time going to the page, so they need to select reviewers
	//
	//
	if (!isset($_POST["submitted"])) {

		echo '<form action="assignReviewers.php" method="post" enctype="multipart/form-data">
				Please select the reviewers for journal '.$fname;
		
		//
		// REVIEWER 1
		//
		echo '<br>Reviewer 1: <select name="rev1">';
		
		$query = "SELECT * FROM users WHERE type = 2";
		$result = mysqli_query($con, $query);
		
		echo '<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '</select>';
		
		//
		// REVIEWER 2
		//
		echo '<br>Reviewer 2: <select name="rev2">';
		
		$query = "SELECT * FROM users WHERE type = 2";
		$result = mysqli_query($con, $query);
		
		echo '<br>
				<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '</select>';
		
		// REVIEWER 3
		echo '<br>Reviewer 3: <select name="rev3">';
		
		$query = "SELECT * FROM users WHERE type = 2";
		$result = mysqli_query($con, $query);
		
		echo '<br>
				<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '</select>
				<br>
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="submitted" value=1>
				<input type="submit" value="Assign Reviewers">
			</form>
			';
		
		// End reviewer submission stuff
	}
	
	
	//
	//
	// Otherwise, the user has pressed the "assign reviewers" button from this page:
	//
	//
	else {
		$rev1 = $_POST["rev1"];
		$rev2 = $_POST["rev2"];
		$rev3 = $_POST["rev3"];
		
		// If the editor selected no reviewers, make them try again.
		if ($rev1 == "" and $rev2 == "" and $rev3 == "") {
			echo 'No reviewers were selected, please try again';
			echo '<br>
					<form action="assignReviewers.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value=1>
						<input type="hidden" name="fname" value='.$fname.'>
						<input type="submit" value="Try Again">
					</form>';
		}
		
		
		// Otherwise, add reviewers to reviewers table and then set the status of the journal to 1
		else {
			// If the first reviewer was selected, add it to reviewers table
			if ($rev1 != "") {
				$query = "INSERT INTO reviewers(journalName, reviewer, decision) VALUES ('".$fname."','".$rev1."',0)";
				mysqli_query($con, $query);
			}
			
			// If the second reviewer was selected, add it to reviewers table
			if ($rev2 != "" && $rev2 != $rev1) {
				$query = "INSERT INTO reviewers(journalName, reviewer, decision) VALUES ('".$fname."','".$rev2."',0)";
				mysqli_query($con, $query);
			}
			
			// If the third reviewer was selected, add it to reviewers table
			if ($rev3 != "" && $rev3 != $rev1 && $rev3 != $rev2) {
				$query = "INSERT INTO reviewers(journalName, reviewer, decision) VALUES ('".$fname."','".$rev3."',0)";
				mysqli_query($con, $query);
			}
			
			// Finally, set status of the journal to 1
			$query = "UPDATE journals SET status=1 WHERE name='".$fname."'";
			mysqli_query($con, $query);
			
			echo '<br>
					<form action="login.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value=1>
						<input type="submit" value="Success!">
					</form>';
		}
		
		
	}
	
?>

<div id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="lgdin" value=1>			
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div id="button">
<form action="index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>

</body>
</html>