<!--

viewUserComs.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for reviewers to view the comments that they have made on the selected journal.
Reviewers are also given a text box to submit new comments on the journal as well as buttons to accept or reject the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	fname		- the filename of the journal the user wishes to view the comments for
	comment		- the comment submitted by the reviewer
	sortByCol	- posted if the user wishes to sort the table by a specified column

--->
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
	
	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify


    // Taken from https://www.php.net/manual/en/function.readfile.php
	// This piece of code downloads a file if a fileName is posted
	//  - Called from this page
    if (isset($_POST["fileName"])) {
        $fileName = $_POST["fileName"];
		$original = $_POST["original"];
		if($original){
			$file = "journals\\".$fileName;
		}else{
			$file = "journals\\revisions\\".$fileName;			
		}
		
		echo "<p>file $file</p>";
		if(file_exists($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
		}
        echo 'ERROR: FILE NOT FOUND';
    }

	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	$fname = $_POST["fname"];
	$submitter = $_POST["submitter"];
	$reviewer = $_POST["reviewer"];
	
	
	
	// updates db with editors decision on refresh
	if(isset($_POST["review"]) and $_POST["review"]==1){
		echo "<p>You have made Major Revisions necessary for $fname by $submitter</p>";
		$query = "UPDATE journals SET status=2 WHERE name='$fname'";
		mysqli_query($con,$query);
	}else if(isset($_POST["review"]) and $_POST["review"]==2){		
		echo "<p>You have made Minor Revisions necessary for $fname by $submitter</p>";
		$query = "UPDATE journals SET status=3 WHERE name='$fname'";
		mysqli_query($con,$query);
	}else if(isset($_POST["review"]) and $_POST["review"]==3){		
		echo "<p>You have Accepted $fname by $submitter</p>";
		$query = "UPDATE journals SET status=4 WHERE name='$fname'";
		mysqli_query($con,$query);
	}else if(isset($_POST["review"]) and $_POST["review"]==4){		
		echo "<p>You have Rejected $fname by $submitter</p>";
		$query = "UPDATE journals SET status=5 WHERE name='$fname'";
		mysqli_query($con,$query);
	}
	
	
	// add comment to db
	if(isset($_POST["comment"]) and $_POST["comment"] != ""){
		$comment = $_POST["comment"];
		
		$query = "INSERT INTO comments VALUES ('$fname','$username','$comment')";
		if(!mysqli_query($con,$query)){
			echo "<p>Error during adding comment. Please try again.</p>";
		}
	}



	// display original and all revised versions of the selected journal
	$query = "SELECT * FROM journals WHERE name='$fname'";
	echo "<p>All Versions of $fname Submitted by $submitter</p>";
	echo '<table>
			<tr>
				<th>Version</th>
				<th>Journal Name</th>
				<th>Submission Date</th>
				<th></th>
			</tr>
	';
	
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	echo '<tr>
			<td>Original</td>
			<td>'.$row["name"].'</td>
			<td>'.$row["submissionDateTime"].'</td>
			<td>
				<div id="button">
				<form action="editorViewComs.php" method="post">
					<input type="hidden" name="username" value='.$username.'>
					<input type="hidden" name="lgdin" value=1>
					<input type="hidden" name="fname" value'.$fname.'>
					<input type="hidden" name="submitter" value'.$submitter.'>
					<input type="hidden" name="reviewer" value'.$reviewer.'>
					<input type="hidden" name="fileName" value='.$row["name"].'>
					<input type="hidden" name="original" value=1>
					<input type="submit" value="Download Journal">
				</form>
				</div>
			</td>
		  </tr>
	';
	
	$query = "SELECT * FROM revisions WHERE originalName='$fname' ORDER BY version";
	$result = mysqli_query($con, $query);
		// While we can pull rows from the database given the query we made...
	while ($row = mysqli_fetch_array($result)) {
		// Show the journalName, submitter, then submissionDateTime, then a button for editing comments, and a button for downloading the journal
		echo '<tr>
				<td>'.$row["version"].'</td>
				<td>'.$row["revisionName"].'</td>
				<td>'.$row["date"].'</td>
				<td>
					<div id="button">
					<form action="editorViewComs.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value=1>
						<input type="hidden" name="fname" value'.$fname.'>
						<input type="hidden" name="submitter" value'.$submitter.'>
						<input type="hidden" name="reviewer" value'.$reviewer.'>
						<input type="hidden" name="fileName" value='.$row["revisionName"].'>
						<input type="hidden" name="original" value=0>
						<input type="submit" value="Download Journal">
					</form>
					</div>
				</td>
			  </tr>
		';
	}
	echo '</table>
	';
		
		

	// display all comments made on the journal
	echo "<p>Comments made on ". $fname. "</p>";
	$query = "SELECT * FROM comments WHERE journalName = '$fname'";
	$result = mysqli_query($con, $query);
	if(!mysqli_num_rows($result)){
		echo '<p>No comments have been made for this Journal</p>';
	}else{
		$num = 1;
		// print comments
		echo '
		<table>
				<tr>
				<th>Number</th>
				<th>Reviewer</th>
				<th>Comment</th>
				</tr>';
			while ($row = mysqli_fetch_array($result)) {
				echo  "<tr>".
					  "  <td>".$num."</td>" .
					  "  <td>".$row['reviewer']."</td>" .
					  "  <td>".$row['comment']."</td>" .
				  "</tr>";
				$num = $num + 1;
			}
			echo "
			</table>";
	}

	
	
	
	// displays editor options for the journal
	// OR
	// displays the review deadline and all reviewers that have not completed their reviews
	$query = "SELECT * FROM reviewers WHERE reviewer = '$username' and journalName = '$fname'";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	$query = "SELECT * FROM journals WHERE name = '$fname' AND NOT EXISTS (SELECT * FROM reviewers WHERE journalName = '$fname' AND decision = 0)";
	$result = mysqli_query($con,$query);
	$row1 = mysqli_fetch_array($result);
	if(mysqli_num_rows($result)){
		echo '
			<p>All assigned reviewers have completed their reviews</p>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return major()">
				Major Revisions Comments
				<input type="text" name="comment" required>
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="submitter" value'.$submitter.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="reviewer" value'.$reviewer.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="review" value=1>
				<input type="submit" value="Major">
			</form>
			</div>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return minor()">
				Minor Revisions Comments
				<input type="text" name="comment" required>
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="submitter" value'.$submitter.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="reviewer" value'.$reviewer.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="review" value=2>
				<input type="submit" value="Minor">
			</form>
			</div>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return accept()">
				Add Acceptance Comments
				<input type="text" name="comment" required>
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="submitter" value'.$submitter.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="reviewer" value'.$reviewer.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="review" value=3>
				<input type="submit" value="Accept">
			</form>
			</div>
			<div id="button">
			<form action="editorViewComs.php" method="post" onsubmit="return reject()">
				Add Rejection Comments
				<input type="text" name="comment" required>
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="hidden" name="submitter" value'.$submitter.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="reviewer" value'.$reviewer.'>			<!--BUG submitter and reviewer are not being posted.....--->
				<input type="hidden" name="fname" value='.$fname.'>
				<input type="hidden" name="review" value=4>
				<input type="submit" value="Reject">
			</form>
			</div>
		';
	}else{
		echo "<p>Not all reviewers have completed their reviews</p>";
		$query = "SELECT * FROM journals WHERE name = '$fname'";
		$result = mysqli_query($con,$query);
		$row = mysqli_fetch_array($result);
		$deadline = $row["deadline"];
		echo "<p>Deadline for the reviews is on $deadline</p>";
		echo "<table>
				<tr>
					<th>Reviewer</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th></th>
				</tr>
		";
		$query = "SELECT * FROM users, reviewers, journals WHERE submitter = '$submitter' AND userName = submitter AND name = journalName AND name = '$fname' AND decision = 0";
		$result = mysqli_query($con,$query);
		while ($row = mysqli_fetch_array($result)) {
			echo  "<tr>".
				  "  <td>".$row['userName']."</td>" .
				  "  <td>".$row['firstName']."</td>" .
				  "  <td>".$row['lastName']."</td>" .
				  "</tr>";
		}
		echo "
		</table>";
	}

	// Close the mysql connectiion
    mysqli_close($con);
?>


<div id="button">
<form action="complete.php" method="post">
    <input type="hidden" name="username" value="<?php echo $username; ?>">
	<input type="hidden" name="submitter" value="<?php echo $submitter; ?>">
	<input type="hidden" name="reviewer" value="<?php echo $reviewer; ?>">
    <input type="hidden" name="lgdin" value=1>
	<input type="submit" value="Return to Journals to Completed Journals Page">
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

<script>
function major() {
	if(confirm("Are you sure you want to require Major Revisions for this Journal?")){
		return true;
	}else{
		return false;
	}
}
function minor() {
	if(confirm("Are you sure you want to require Minor Revisions for this Journal?")){
		return true;
	}else{
		return false;
	}
}
function accept() {
	if(confirm("Are you sure you want to Accept this Journal?")) {
		return true;
	}else{
		return false;
	}
}
function reject() {
	if(confirm("Are you sure you want to Reject this Journal?")){
		return true;
	}else{
		return false;
	}
}
</script>
</body>
</html>