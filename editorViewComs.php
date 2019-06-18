<!--

editorViewComs.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for editors to view the comments that have been made on the selected journal.
Editors can ask for revisions or make their final decision for the journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	fname		- the filename of the journal the user wishes to view the comments for
	comment		- the comment submitted by the reviewer
	sortByCol	- posted if the user wishes to sort the table by a specified column
	filename	- the filename of the file the editor would like to downloading
	original	- whether the file was the original submission or a revision
	
-->
<html>
<head>
<title>Comments</title>
<link href="styleeditorcoms.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<h1>Comments</h1>
<?php
	if (!isset($_POST["lgdin"]) or !isset($_POST["username"])) {
        echo '
	<div class="pleaseLogin">
		<p>Please Login</p>
	</div>';
        echo '
	<div class="buttons">
		<form action="..\index.php" method="post">
            <input type="submit" value="Return to Menu">
        </form>
	</div>';
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
    if(isset($_POST["fileName"])) {
        $fileName = $_POST["fileName"];
		$original = $_POST["original"];
		if($original){
			$file = "journals\\".$fileName;
		}else{
			$file = "journals\\revisions\\".$fileName;			
		}
		
		if(file_exists($file)){
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
		}else{
			echo 'ERROR: FILE NOT FOUND';
		}
    }

	$username = $_POST["username"];
	$lgdin = $_POST["lgdin"];
	$fname = $_POST["fname"];
	$submitter = $_POST["submitter"];
	$returnPage = $_POST["returnPage"];
	
	// updates db with editors decision on refresh
	if(isset($_POST["review"]) and $_POST["review"]==1){
		echo '
<div class="update">
	<p>You have made Major Revisions necessary for '.$fname.' by '.$submitter.'</p>
</div>';
		$query = "UPDATE journals SET status=2 WHERE name='$fname'";
		mysqli_query($con,$query);
		// Also reset the reviewers decisions
		$query = "UPDATE reviewers SET decision=0 WHERE journalName='$fname'";
		mysqli_query($con,$query);
		
	} else if(isset($_POST["review"]) and $_POST["review"]==2) {		
		echo '
<div class="update">
	<p>You have made Minor Revisions necessary for '.$fname.' by '.$submitter.'</p>
</div>';
		$query = "UPDATE journals SET status=3 WHERE name='$fname'";
		mysqli_query($con,$query);
		// Also reset the reviewers decisions
		$query = "UPDATE reviewers SET decision=0 WHERE journalName='$fname'";
		mysqli_query($con,$query);
		
	} else if(isset($_POST["review"]) and $_POST["review"]==3) {		
		echo '
<div class="update">
	<p>You have Accepted '.$fname.' by '.$submitter.'</p>
</div>';
		$query = "UPDATE journals SET status=4 WHERE name='$fname'";
		mysqli_query($con,$query);
		
	} else if(isset($_POST["review"]) and $_POST["review"]==4) {		
		echo '
<div class="update">
	<p>You have Rejected '.$fname.' by '.$submitter.'</p>
</div>';
		$query = "UPDATE journals SET status=5 WHERE name='$fname'";
		mysqli_query($con,$query);
	}
	
	
	// add comment to db
	if(isset($_POST["comment"]) and $_POST["comment"] != ""){
		$comment = $_POST["comment"];
		
		$query = "INSERT INTO comments VALUES ('$fname','$username','$comment')";
		if(!mysqli_query($con,$query)){
			echo '
<div class="update">
	<p>Error during adding comment. Please try again.</p>
</div>';
		}
	}



	// display original and all revised versions of the selected journal
	$query = "SELECT * FROM users WHERE userName='$submitter'";
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	echo '
<table class="page">
	<tr>
		<th>
			<div class="title">
				<h2>All versions of '.$fname.' submitted by '.$row["firstName"].' '.$row["lastName"].'</h2>
			</div>

			<div class="infoTables">
			<table class="journal">
				<tr class="row">
					<th class="head">Version</th>
					<th class="head">Journal Name</th>
					<th class="head">Submission Date</th>
					<th class="head"></th>
				</tr>';
	
	$query = "SELECT * FROM journals WHERE name='$fname'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_array($result);
	echo '
				<tr class="row">
					<td class="entry">Original</td>
					<td class="entry">'.$row["name"].'</td>
					<td class="entry">'.$row["submissionDateTime"].'</td>
					<td class="entry">
						<div id="button">
							<form action="editorViewComs.php" method="post">
								<input type="hidden" name="username" value='.$username.'>
								<input type="hidden" name="lgdin" value=1>
								<input type="hidden" name="fname" value='.$fname.'>
								<input type="hidden" name="submitter" value='.$submitter.'>
								<input type="hidden" name="fileName" value='.$row["name"].'>
								<input type="hidden" name="original" value=1>
								<input type="submit" value="Download Journal">
							</form>
						</div>
					</td>
				 </tr>';
	
	$query = "SELECT * FROM revisions WHERE originalName='$fname' ORDER BY version";
	$result = mysqli_query($con, $query);
		// While we can pull rows from the database given the query we made...
	while ($row = mysqli_fetch_array($result)) {
		// Show the journalName, submitter, then submissionDateTime, then a button for editing comments, and a button for downloading the journal
		echo '
				<tr class="row">
					<td class="entry">'.$row["version"].'</td>
					<td class="entry">'.$row["revisionName"].'</td>
					<td class="entry">'.$row["date"].'</td>
					<td class="entry">
						<div id="button">
							<form action="editorViewComs.php" method="post">
								<input type="hidden" name="username" value='.$username.'>
								<input type="hidden" name="lgdin" value=1>
								<input type="hidden" name="fname" value='.$fname.'>
								<input type="hidden" name="submitter" value='.$submitter.'>
								<input type="hidden" name="fileName" value='.$row["revisionName"].'>
								<input type="hidden" name="original" value=0>
								<input type="submit" value="Download Journal">
							</form>
						</div>
					</td>
				 </tr>';
	}
	echo '
			</table>
			</div>';
		

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
			<div class="reviewJournal">
				<p>All assigned reviewers have completed their reviews</p>
				<table class="finalButtons">
					<div id="button1">
						<tr>
							<td>
								<form action="editorViewComs.php" method="post" onsubmit="return major()">
									Major Revisions Comments
									<input type="text" name="comment" required>
									<input type="hidden" name="username" value='.$username.'>
									<input type="hidden" name="lgdin" value=1>
									<input type="hidden" name="fname" value='.$fname.'>
									<input type="hidden" name="submitter" value='.$submitter.'>
									<input type="hidden" name="review" value=1>
									<input type="submit" value="Major">
								</form>
							</td>
							<td>
								<form action="editorViewComs.php" method="post" onsubmit="return minor()">
									Minor Revisions Comments
									<input type="text" name="comment" required>
									<input type="hidden" name="username" value='.$username.'>
									<input type="hidden" name="lgdin" value=1>
									<input type="hidden" name="fname" value='.$fname.'>
									<input type="hidden" name="submitter" value='.$submitter.'>
									<input type="hidden" name="review" value=2>
									<input type="submit" value="Minor">
								</form>
							</td>
						</tr>
						<tr>
							<td>
								<form action="editorViewComs.php" method="post" onsubmit="return accept()">
									Add Acceptance Comments
									<input type="text" name="comment" required>
									<input type="hidden" name="username" value='.$username.'>
									<input type="hidden" name="lgdin" value=1>
									<input type="hidden" name="fname" value='.$fname.'>
									<input type="hidden" name="submitter" value='.$submitter.'>
									<input type="hidden" name="review" value=3>
									<input type="submit" value="Accept">
								</form>
							</td>
							<td>
								<form action="editorViewComs.php" method="post" onsubmit="return reject()">
									Add Rejection Comments
									<input type="text" name="comment" required>
									<input type="hidden" name="username" value='.$username.'>
									<input type="hidden" name="lgdin" value=1>
									<input type="hidden" name="fname" value='.$fname.'>
									<input type="hidden" name="submitter" value='.$submitter.'>
									<input type="hidden" name="review" value=4>
									<input type="submit" value="Reject">
								</form>
							</td>
						</tr>
					</div>
				</table>
			</div>';
	}else{
		echo '
			<div class="reviewJournal">
				<p>Not all reviewers have completed their reviews</p>
			</div>';
		$query = "SELECT * FROM journals WHERE name = '$fname'";
		$result = mysqli_query($con,$query);
		$row = mysqli_fetch_array($result);
		$deadline = $row["deadline"];
		echo '
			<div class="deadlineMsg">
				<h4>Deadline for the reviews is on '.$deadline.'</h4>
			</div>';
		echo '
			<table class="reviewPref">
				<tr class="row">
					<th class="head">Reviewer</th>
					<th class="head">First Name</th>
					<th class="head">Last Name</th>
				</tr>';
		$query = "SELECT * FROM users, reviewers, journals WHERE userName = reviewer AND name = journalName AND name = '$fname' AND decision = 0";
		$result = mysqli_query($con,$query);
		while ($row = mysqli_fetch_array($result)) {
			echo  '
				<tr class="row">
					<td class="entry">'.$row["userName"].'</td>
					<td class="entry">'.$row["firstName"].'</td>
					<td class="entry">'.$row["lastName"].'</td>
				</tr>';
		}
		echo "
			</table>";
	}
	echo '
		</th>
		<th>';



	// display all comments made on the journal
	echo '
			<div class="comTitle">
					<h2>Comments made on '. $fname. '</h2>
			</div>';
	$query = "SELECT * FROM comments WHERE journalName = '$fname'";
	$result = mysqli_query($con, $query);
	if(!mysqli_num_rows($result)){
		echo '
			<div class="infoTables">
				<div class="comments">
					<p>No comments have been made for this Journal</p>
				</div>
			</div>';
	}else{
		$num = 1;
		// print comments
		echo '
			<table class="comments">
				<tr class="row">
					<th class="head">Number</th>
					<th class="head">Reviewer</th>
					<th class="head">Comment</th>
				</tr>';
		while ($row = mysqli_fetch_array($result)) {
			echo  '
				<tr class="row">
					<td class="entry">'.$num.'</td>
					<td class="entry">'.$row['reviewer'].'</td>
					<td class="entry">'.$row['comment'].'</td>
				</tr>';
			$num = $num + 1;
		}
		echo '
			</table>';
	}
	echo '
		</th>
	</tr>
</table>';
	
	


	if($returnPage == 0){
		echo '
<div class="buttons">
	<div class="returnButton" id="button">
		<form action="viewAssigned.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to Assigned Journals Page">
		</form>
	</div>
</div>';		
	}else if($returnPage == 1){
		echo '
<div class="buttons">
	<div class="returnButton" id="button">
		<form action="complete.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to Completed Journals Page">
		</form>
	</div>
</div>';
	}else if($returnPage == 2){
		echo '
<div class="buttons">
	<div class="returnButton" id="button">
		<form action="viewAll.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to All Journals Page">
		</form>
	</div>
</div>';		
	}else{
		echo '
<div class="buttons">
	<div class="returnButton" id="button">
		<form action="viewAccepted.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Return to Accepted Journals Page">
		</form>
	</div>
</div>';		
	}
	// Close the mysql connectiion
    mysqli_close($con);
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
