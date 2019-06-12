<!--

review.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This page is for reviewers to view the journals that they have been assigned to review.
The reviewer is given the option to view their comments on the journal, a button to download the journal and the reviewers decision.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
	review		- the reviewers decision
	fname		- the filename of the journal the user wishes to view the comments for
	sortByCol	- posted if the user wishes to sort the table by a specified column

-->
<html>
<head>
<title>Review</title>
<link href="stylereview.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="rectangle"></div>
<?php

	// Check if user is logged in
    if (!isset($_POST["lgdin"]) or !isset($_POST["username"])) {
        echo '<div class="pleaseLogin"><p>Please Login</p></div>';
        echo '<div class="buttons"><form action="..\index.php" method="post">
                <input type="submit" value="Return to Menu">
              </form> </div>	
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
		
		$query = "SELECT * FROM journals WHERE name='$fileName'";
		$result = mysqli_query($con,$query);
		$row = mysqli_fetch_array($result);
		if($row["version"]>0){
			$query = "SELECT * FROM revisions WHERE originalName='$fileName' and version=".$row["version"];
			$result = mysqli_query($con,$query);
			$row = mysqli_fetch_array($result);
			$file = "journals\\revisions\\".$row["revisionName"];
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
		}
        echo 'ERROR: FILE NOT FOUND';
    }

	// add reviewers decision to the db is review accepted or rejected journal
	if(isset($_POST["review"]) and isset($_POST["fname"])){
		$review = $_POST["review"];
		$fname = $_POST["fname"];
		$query = "UPDATE reviewers SET decision=$review WHERE journalName='$fname' and reviewer='$username'";
		mysqli_query($con,$query);	
	}
	
	
	// Select all rows from journals and reviewers where the journalName == name and the reviewer == the current user
    $query = "SELECT * FROM journals, reviewers WHERE journalName = name AND reviewer = '$username' AND (decision=0 or status=1)";
    
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
            $query = $query." ORDER BY journalName";
        } else if ($sortByCol == 1) {
            $query = $query." ORDER BY submitter";
        } else if ($sortByCol == 2) {
            $query = $query." ORDER BY submissionDateTime";
        }
    }
    
    $result = mysqli_query($con, $query);
    
    if ($result) {
        echo '<h1>Journals</h1>';
		// The button starts at <div id="sortButton"> and ends at </div>
		// <form action="review.php" means it points to itself (review.php) when you press the button, and method="post"> means it posts some info and goes to that page
		// <input type="hidden" means that what we're about to add to the post isn't actually visible to the user.  name="username" is the variable name we're posting, value is the value of that variable that we post.
		// Finally, the last line is the actual name of the button and the "submit" action.
        echo '
<table class="journals">
    <tr>
        <th>
            <div id="sortButton">
                <form action="review.php" method="post">
                    <input type="hidden" name="username" value='.$username.'>
                    <input type="hidden" name="lgdin" value=1>			
                    <input type="submit" value="Journal Name">
                </form>
            </div>
        </th>
        <th>
            <div id="sortButton">
                <form action="review.php" method="post">
                    <input type="hidden" name="username" value='.$username.'>
                    <input type="hidden" name="sortByCol" value=1>
                    <input type="hidden" name="lgdin" value=1>			
                    <input type="submit" value="Submitter">
                </form>
            </div>
        </th>
        <th>
            <div id="sortButton">
                <form action="review.php" method="post">
					<input type="hidden" name="username" value='.$username.'>
                    <input type="hidden" name="sortByCol" value=2>
                    <input type="hidden" name="lgdin" value=1>
                    <input type="submit" value="Submission Date">
                </form>
            </div>
        </th>
        <th>
        </th>
        <th>
        </th>
        <th>
        </th>
    </tr>';
        
		// While we can pull rows from the database given the query we made...
        while ($row = mysqli_fetch_array($result)) {
			// Show the journalName, submitter, then submissionDateTime, then a button for editing comments, and a button for downloading the journal
            echo '
	<tr>
		<td>'.$row["journalName"].'</td>
        <td>'.$row["submitter"].'</td>
		<td>'.$row["submissionDateTime"].'</td>
		<td>
			<div id="button">
				<form action="viewUserComs.php" method="post">
					<input type="hidden" name="username" value='.$username.'>
					<input type="hidden" name="lgdin" value=1>
					<input type="hidden" name="fname" value='.$row["journalName"].'>
					<input type="submit" value="View Comments">
				</form>
			</div>
		</td>
		<td>
			<div id="button">
				<form action="review.php" method="post">
					<input type="hidden" name="username" value='.$username.'>
					<input type="hidden" name="lgdin" value=1>
					<input type="hidden" name="sortByCol" value='.$sortByCol.'>
					<input type="hidden" name="fileName" value='.$row["journalName"].'>
					<input type="submit" value="Download Journal">
				</form>
			</div>
		</td>';
			if($row["decision"] == 0){
				echo	'
		<td>You have not made a decision on this journal yet</td>';
			}else if($row["decision"] == 1){
				echo	'
		<td>You have Accepted this Journal</td>';
			}else{
				echo	'
		<td>You have Rejected this Journal</td>';					
			}
            echo	'
	</tr>';
        }
        echo '
</table>';
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
	<form action="../index.php" method="post">
		<input type="submit" value="Logout">
	</form>	
	</div>
</div>
</body>
</html>
