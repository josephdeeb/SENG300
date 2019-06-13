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

--> 
<html>
<head>
<title>Assign Reviewer</title>
<!--
<link href="styleassignreviewer.css" type="text/css" rel="stylesheet" />
-->
</head>
<body>
<div class="rectangle"></div>
<?php
    // Make sure the user is logged in
	if(!isset($_POST["lgdin"]) or !isset($_POST["username"])){
	  echo '
<p>Please Login</p>
<form action="index.php" method="post">
	<input type="submit" value="Return to Login Page">
</form>';
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

		echo '
<form action="assignReviewers.php" method="post" enctype="multipart/form-data">
	<h2>Please select the reviewers for journal '.$fname.'</h2>';
		
		//
		// REVIEWER 1
		//
		echo '
	<br>Reviewer 1: <select name="rev1">';
		
		$query = "SELECT * FROM users WHERE type = 2 AND NOT EXISTS (SELECT * FROM journals WHERE userName=submitter AND name='$fname')";
		$result = mysqli_query($con, $query);
		
		echo '
		<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '
		<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '
	</select>';
		
		//
		// REVIEWER 2
		//
		echo '
	<br>Reviewer 2: <select name="rev2">';
		
		$query = "SELECT * FROM users WHERE type = 2 AND NOT EXISTS (SELECT * FROM journals WHERE userName=submitter AND name='$fname')";
		$result = mysqli_query($con, $query);
		
		echo '
		<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '
		<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '
	</select>';
		
		// REVIEWER 3
		echo '
	<br>Reviewer 3: <select name="rev3">';
		
		$query = "SELECT * FROM users WHERE type = 2 AND NOT EXISTS (SELECT * FROM journals WHERE userName=submitter AND name='$fname')";
		$result = mysqli_query($con, $query);
		
		echo '
		<option value="">Select a Reviewer</option>';
		while ($row = mysqli_fetch_array($result)) {
			echo '
		<option value='.$row["userName"].'>'.$row["firstName"].' '.$row["lastName"].'</option>';
		}
		echo '
	</select>
	<br><br>
	<input type="hidden" name="username" value='.$username.'>
	<input type="hidden" name="lgdin" value=1>
	<input type="hidden" name="fname" value='.$fname.'>
	<input type="hidden" name="submitted" value=1>
	<input type="submit" value="Assign Above Reviewers">
</form>';
		
		// End reviewer submission stuff

		$query = "SELECT * FROM users, subPrefs WHERE journalName='$fname' AND userName=reviewer ORDER BY preferred";
		$result = mysqli_query($con, $query);
		if (mysqli_num_rows($result)>0) {
			$query = "SELECT * FROM users, journals WHERE name='$fname' AND userName=submitter";
			$result1 = mysqli_query($con, $query);
			$row = mysqli_fetch_array($result1);
			echo "
<h2>".$row["firstName"]." ".$row["lastName"]."'s Preferred and Non-Preferred Reviewers</h2>";

			echo '
<table>
	<tr>
		<th>Reviewer</th>
		<th></th>
	</tr>';
			
			// While we can pull rows from the database given the query we made...
			while ($row = mysqli_fetch_array($result)) {
				$prefer = "Not Preferred";
				if($row["preferred"] == 1){
					$prefer = "Preferred";
				}
				
				echo '
	<tr>
		<td>'.$row["firstName"].' '.$row["lastName"].'</td>
		<td>'.$prefer.'</td>
	</tr>';
			}
			echo '
</table>';
		}else{
			$query = "SELECT * FROM users, journals WHERE name='$fname' AND submitter=userName";
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			echo "
<h2>".$row["firstName"]." ".$row["lastName"]." has not set any preferences</h2>";
		}



// Show reviewer preferences

		// get all reviewers
		$query = "SELECT * FROM users WHERE type=2";
		$result = mysqli_query($con, $query);

		while ($row = mysqli_fetch_array($result)) {
			// get preferences of reviewer
			$query = "SELECT * FROM users, revprefs WHERE reviewer='".$row["userName"]."' AND userName=reviewer";
			$result1 = mysqli_query($con,$query);
			if(mysqli_num_rows($result1)>0) {
				echo "
<h2>".$row["firstName"]." ".$row["lastName"]."'s Preferred Authors</h2>";
				echo '
<table>
	<tr>
		<th>Author</th>
	</tr>';
			
				while($row1 = mysqli_fetch_array($result1)){
					$query = "SELECT * FROM users WHERE userName='".$row1["submitter"]."'";
					$result2 = mysqli_query($con,$query);
					$row2 = mysqli_fetch_array($result2);
					echo '
	<tr>
		<td>'.$row2["firstName"].' '.$row2["lastName"].'</td>
	</tr>';
				}
			echo '
</table>';
			}
		}
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
			echo '
No reviewers were selected, please try again
<br>
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
				$query = "UPDATE `journals` SET `deadline`= DATE_ADD(NOW(), INTERVAL 3 MONTH) WHERE name='$fname'";
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
			
			echo '
<br>
<form action="login.php" method="post">
	<input type="hidden" name="username" value='.$username.'>
	<input type="hidden" name="lgdin" value=1>
	<input type="submit" value="Success!">
</form>';
		}
		
		
	}
	
?>
<div class="buttons">
<div class="returnMenuButton" id="button">
<form action="login.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="lgdin" value=1>			
	<input type="submit" value="Return to Main Menu">
</form>
</div>
<div class="logoutButton" id="button">
<form action="index.php" method="post">
	<input type="submit" value="Logout">
</form>	
</div>
</div>
</body>
</html>
