<!--

complete.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for reviewers to view the journals that they have been assigned to review.
The reviewer is given the option to view their comments on the journal, a button to download the journal and the reviewers decision.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	submitter	- posted if the user wishes to view all journals from a specific submitter
	reviewer	- posted if the user wishes to view all journals from a specific reviewer
	sortByCol	- posted if the user wishes to sort the table by a specified column
	filename	- posted if the user wishes to download a journal

--->
<html>
<body>

<?php
	
	// Check if user is logged in
    if (!isset($_POST["lgdin"]) or !isset($_POST["username"])) {
        echo "<p>Please Login</p>";
        echo '<form action="..\index.php" method="post">
                <input type="submit" value="Return to Login Page">
              </form>	
        ';
        die();
    }
    
    $username = $_POST["username"];
    
    // Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
    
    // Taken from https://www.php.net/manual/en/function.readfile.php
	// This piece of code downloads a file if a fileName is posted
	//  - Called from this page
    if (isset($_POST["fileName"])) {
        $fileName = $_POST["fileName"];
		$file = "journals\\".$fileName;
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

	
	if(isset($_POST["submitter"]) and $_POST["submitter"] != ""){
		$sub = $_POST["submitter"];
		$query = "SELECT * FROM journals WHERE submitter='$sub'";
		$skip = 0;
	}else if(isset($_POST["reviewer"]) and $_POST["reviewer"] != ""){
		$rev = $_POST["reviewer"];
		$query = "SELECT * FROM journals, reviewers WHERE reviewer='$rev' and journalName=name";
		$skip = 0;
	}else{
		echo "<p>Please select which journals you wish to view.</p>";
		$skip = 1;
	}
	
	if(!$skip){
		// If the variable "sortByCol" was posted, 0 == by journalName, 1 by submitter, 2 by submissionDateTime
		if (isset($_POST["sortByCol"])) {
			$sortByCol = $_POST["sortByCol"];
			if ($sortByCol == 2) {
				$sort = 2;
			} else {
				$sort = 1;
			}
		} else {
			$sort = 0;
			$sortByCol = 0;
		}
		
		// Add to the end of query so we order by the appropriate column
		if ($sort > 0) {
			if ($sortByCol == 0) {
				$query = $query." ORDER BY name";
			} else if ($sortByCol == 1) {
				$query = $query." ORDER BY submitter";
			} else if ($sortByCol == 2) {
				$query = $query." ORDER BY submissionDateTime";
			}
		}
		
		$result = mysqli_query($con, $query);
		
		if (mysqli_num_rows($result)>0) {
			echo '<p>Journals</p>
			';
			// The button starts at <div id="sortButton"> and ends at </div>
			// <form action="review.php" means it points to itself (review.php) when you press the button, and method="post"> means it posts some info and goes to that page
			// <input type="hidden" means that what we're about to add to the post isn't actually visible to the user.  name="username" is the variable name we're posting, value is the value of that variable that we post.
			// Finally, the last line is the actual name of the button and the "submit" action.
			echo '<table>
					<tr>
					<th>
						<div id="sortButton">
						<form action="complete.php" method="post">
							<input type="hidden" name="username" value='.$username.'>
							<input type="hidden" name="lgdin" value=1>			
							<input type="submit" value="Journal Name">
						</form>
						</div>
					</th>
					<th>
						<div id="sortButton">
						<form action="complete.php" method="post">
							<input type="hidden" name="username" value='.$username.'>
							<input type="hidden" name="sortByCol" value=1>
							<input type="hidden" name="lgdin" value=1>			
							<input type="submit" value="Submitter">
						</form>
						</div>
					</th>
					<th>
						<div id="sortButton">
						<form action="complete.php" method="post">
							<input type="hidden" name="username" value='.$username.'>
							<input type="hidden" name="sortByCol" value=2>
							<input type="hidden" name="lgdin" value=1>
							<input type="submit" value="Submission Date">
						</form>
						</div>
					</th>
					<th>
					</th>
					</tr>';
			
			// While we can pull rows from the database given the query we made...
			while ($row = mysqli_fetch_array($result)) {
				// Show the journalName, submitter, then submissionDateTime, then a button for editing comments, and a button for downloading the journal
				echo '<tr>
						<td>'.$row["name"].'</td>
						<td>'.$row["submitter"].'</td>
						<td>'.$row["submissionDateTime"].'</td>
						<td>
							<div id="button">
							<form action="editorViewComs.php" method="post">
								<input type="hidden" name="username" value='.$username.'>
								<input type="hidden" name="lgdin" value=1>
								<input type="hidden" name="fname" value='.$row["name"].'>
								<input type="hidden" name="subName" value='.$row["submitter"].'>
								<input type="submit" value="View Journal">
							</form>
							</div>
						</td>
						';
				echo	'</tr>
				';
			}
			echo '</table>
			';
		}else{
			echo "<p>This user has no journals associated with them</p>
			";
		}
	}
		// submit journal
	echo '  <form action="complete.php" method="post" enctype="multipart/form-data">
				Submitters: <select name="submitter">
										';
	$query = "SELECT * FROM users WHERE type = 1";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Submitter</option>';
	while($row = mysqli_fetch_array($result)){			
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>
				Reviewers: <select name="reviewer">
										';
	$query = "SELECT * FROM users WHERE type = 2";
	$result = mysqli_query($con,$query);
	echo '								<option value="">Select a Reviewer</option>';
	while($row = mysqli_fetch_array($result)){
		echo '								<option value='.$row["userName"].'>'.$row["firstName"]. ' '. $row["lastName"]. '</option>';
	}
	echo ' </select>
		   <br>';
		echo '	<input type="hidden" name="username" value='.$username.'>
				<input type="hidden" name="lgdin" value=1>
				<input type="submit" value="View Journals">
			</form>
		 ';

    // Close the mysql connectiion
    mysqli_close($con);
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