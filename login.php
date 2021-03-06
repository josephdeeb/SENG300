<!--
login.php
----------------------------------------------------------------------------------------------------------------------------------------------------
If the user fails to login successfully, they will be told and shown a return to main menu buttons.
If a user has successfully logged in, this page will be displayed in full.
This page displays buttons corresponding of the type of user that they are (submitter, reviewer, editor).
----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username 	- the username of the user attempting to log in
	password	- the entered password for given userName
	lgdin		- posted when logged in user is returning to main menu
	sortByCol	- a variable corresponding to the column that the user would like the submitted journals table to be sorted by
---->

<html>
<head>
<title>Main Menu</title>
<link href="stylelogin.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>


<?php
	if(!isset($_POST["lgdin"])){
		if(!isset($_POST["username"]) or !isset($_POST["password"])){
		  echo '
<div class="pleaseLogin"><p>Please Login</p></div>';
		  echo '
<div class="returnToLogin">
	<form action="..\index.php" method="post">
		<input type="submit" value="Return to Login Page">
	</form>
</div>';
		  die();
		}
	}
	$username = $_POST["username"];
	// Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");
	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// verify that user has logged in
	if(!isset($_POST["lgdin"])){
		$password = $_POST["password"];
		$query = "SELECT password FROM users WHERE userName = '$username'";
		$pw = mysqli_query($con, $query);
		if(!$pw){
		  echo '
<div class="pleaseLogin">
	<p>Please Login</p>
</div>
<div class="returnToLogin">
	<form action="..\index.php" method="post">
		<input type="submit" value="Return to Login Page">
	</form>
</div>';
		  die();
		}else{
		  $row = $pw->fetch_assoc();
		}
		if(strcmp($row['password'], $password) == 0){
		}else{
		  echo '
<div class="loginFailed">
	<p>Login failed </p>
</div>
<div class="returnToLogin">
	<form action="..\index.php" method="post">
		<input type="submit" value="Return to Login Page">
	</form>
</div>';
		  die();
		}
	}
	// user has logged in successfully
    
    if (isset($_POST["deleteJournal"])) {
        $revisionPath = "journals\\revisions\\";
        $journalPath = "journals\\";
        $file = $_POST["deleteJournal"];
        
        $query = "SELECT * FROM revisions WHERE originalName = '$file'";
        $result = mysqli_query($con, $query);
		
        while ($row = mysqli_fetch_array($result)) {
			if (file_exists($revisionPath.$row["revisionName"])) {
				unlink($revisionPath.$row["revisionName"]);
			}
        }
		if (file_exists($journalPath.$file)) {
			unlink($journalPath.$file);
		}
        $query = "DELETE FROM comments WHERE journalName = '$file'";
        mysqli_query($con,$query);
        $query = "DELETE FROM reviewers WHERE journalName = '$file'";
        mysqli_query($con,$query);
        $query = "DELETE FROM revisions WHERE originalName = '$file'";
        mysqli_query($con,$query);
        $query = "DELETE FROM subprefs WHERE journalName = '$file'";
        mysqli_query($con,$query);
        $query = "DELETE FROM journals WHERE name = '$file'";
        mysqli_query($con,$query);
    }
	
	// determine user type in order to show correct information
	$query = "SELECT * FROM users WHERE userName = '$username'";
	$sql = mysqli_query($con,$query);
	$type = $sql->fetch_assoc()['type'];
	if($type == 1){
		echo "
<h1>Logged in as an Author</h1>";
	}else if($type == 2){
		echo "
<h1>Logged in as a Reviewer</h1>";
	}else{
		echo "
<h1>Logged in as an Editor</h1>";
	}
	echo '
<div class="login">';

	//
	//		REVIEWER options
	//	
	
	
	if($type == 2){
	  // give reviewer options
	  echo '
	<div class="reviewHead">
		<h2>Journals to Review</h2>
	</div>
	<div class="buttons">
		<div class="assignedJourButton">
			<form action="review.php" method="post">
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="submit" value="View Assigned Journals">
			</form>	
		</div>
	</div>
	<div class="buttons">
		<div class="editPreferenceButton">
			<form action="revPref.php" method="post">
				<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="submit" value="Edit Submission Preferences">
			</form>	
		</div>
	</div>';
	}
	
	
	//
	//		Author options
	//
	
	if($type == 1 or $type == 2){
			// submit button
	  echo '
	<div class="submitJournalButton">                 
		<form action="submit.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>
			<input type="submit" value="Submit New Journal">
		</form>	
	</div>';
			// display journals
		if(isset($_POST["sortByCol"])){
			$sortByCol = $_POST["sortByCol"];
			$sort = 1;
		}else{
			$sort = 0;
			$sortByCol = 0;
		}
			// order journals by desired column
		$query = "SELECT * FROM journals WHERE submitter = '$username'";
		if($sort == 1){
			if($sortByCol == 0){
				$query = $query." ORDER BY name";
			}else if($sortByCol == 1){	
				$query.=" ORDER BY status";
			}else if($sortByCol == 2){
				$query.=" ORDER BY submissionDateTime";
			}
		}
		$result = mysqli_query($con, $query);
		// print journals
		if(!mysqli_num_rows($result)){
			echo ' 
	<div class="emptyJournals"> 
		You have not submitted any Journals yet
	</div>';
		}else{
			echo '
	<h2>Your Journals</h2>';
			echo '
	<table>
		<tr>
			<th>
				<div id="sortButton">
					<form action="login.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="sortByCol" value=0>
						<input type="hidden" name="lgdin" value=1>			
						<input type="submit" value="Journal Name">
					</form>
				</div>
			</th>
			<th>
				<div id="sortButton">
					<form action="login.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="sortByCol" value=1>
						<input type="hidden" name="lgdin" value=1>			
						<input type="submit" value="Status">
					</form>
				</div>
			</th>
			<th>
				<div id="sortButton">
					<form action="login.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="sortByCol" value=2>
						<input type="hidden" name="lgdin" value=1>			
						<input type="submit" value="Date">
					</form>
				</div>
			</th>
			<th>
			</th>
            <th>
            </th>
		</tr>';
			while ($row = mysqli_fetch_array($result)) {
				if($row['status'] == 0){
					$status = 'Pending Review';
				}else if($row['status'] == 1){
					$status = 'Assigned Reviewers';
				}else if($row['status'] == 2){
					$status = 'Major Revisions Required';
				}else if ($row['status'] == 3){
					$status = 'Minor Revisions Required';
				}else if ($row['status'] == 4){
					$status = 'Accepted';
				}else {
					$status = 'Rejected';
				}
				echo  '
		<tr>
			<td>'.$row["name"].'</td>
			<td>'.$status.'</td>
			<td>'.$row["submissionDateTime"].'</td>
			<td>
				<div id="button">
					<form action="viewComs.php" method="post">
						<input type="hidden" name="username" value='.$username.'>
						<input type="hidden" name="lgdin" value=1>		
						<input type="hidden" name="fname" value='.$row["name"].'>
						<input type="submit" value="View Comments">
					</form>
				</div>								
			 </td>
             <td>
                <div id="button">
                    <form action="login.php" method="post" onsubmit="return confirmation()">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="hidden" name="deleteJournal" value='.$row["name"].'>
                        <input type="submit" value="Delete">
                    </form>
                </div>
             </td>
		</tr>';
			}
			echo '
	</table>';
		}
	}
		
		
	
	//
	//		Editor
	//
	
	
	
	if($type == 3){
	  // give editor options
	  echo '
	<div class = "options">
		<form action="viewUnassignedJournals.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>		
			<input type="submit" value="View Submitted Journals">
		</form>
	</div>
	<div class = "options">
		<form action="viewAssigned.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>		
			<input type="submit" value="View All Assigned Journals">
		</form>
	</div>
	<div class = "options">
		<form action="complete.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>		
			<input type="submit" value="View Completed Reviews">
		</form>
	</div>
	<div class = "options">
		<form action="viewAccepted.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>		
			<input type="submit" value="View Accepted Journals">
		</form>
	</div>
	<div class = "options">
		<form action="viewAll.php" method="post">
			<input type="hidden" name="username" value='.$username.'>
			<input type="hidden" name="lgdin" value=1>		
			<input type="submit" value="View All Journals">
		</form>
	</div>';
	}
	echo '
</div>';
	mysqli_close($con);
?>

<form action="..\index.php" method="post">
	<div class="logoutButton">
		<input type="submit" value="Logout">
	</div>
</form>
<script>
function confirmation() {
    if(confirm("Are you sure you would like to delete this journal?")) {
        return true;
    } else {
        return false;
    }
}
</script>	
</body>
</html>
