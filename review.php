<html>
<body>

<?php

    // Taken from https://www.php.net/manual/en/function.readfile.php
	// This piece of code downloads a file if a fileName is posted
	//  - Called from this page
    if (isset($_POST["fileName"])) {
        $fileName = $_POST["fileName"];
        if (file_exists($fileName)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileName));
            readfile($fileName);
        }
        echo 'ERROR: FILE NOT FOUND';
    }

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
    
	// Select all rows from journals and reviewers where the journalName == name and the reviewer == the current user
    $query = "SELECT * FROM journals, reviewers WHERE journalName = name AND reviewer = '$username'";
    
	// If the variable "sortRow" was posted, 0 == by journalName, 1 by submitter, 2 by submissionDateTime
    if (isset($_POST["sortRow"])) {
        $sortRow = $_POST["sortRow"];
        if ($sortRow == 2) {
            $sort = 2;
        } else {
            $sort = 1;
        }
    } else {
        $sort = 0;
        $sortRow = 0;
    }
    
	// Add to the end of query so we order by the appropriate column
    if ($sort > 0) {
        if ($sortRow == 0) {
            $query = $query." ORDER BY journalName";
        } else if ($sortRow == 1) {
            $query = $query." ORDER BY submitter";
        } else if ($sortRow == 2) {
            $query = $query." ORDER BY submissionDateTime";
        }
    }
    
    $result = mysqli_query($con, $query);
    
    if ($result) {
        echo '<p>Journals</p>';
		// The button starts at <div id="sortButton"> and ends at </div>
		// <form action="review.php" means it points to itself (review.php) when you press the button, and method="post"> means it posts some info and goes to that page
		// <input type="hidden" means that what we're about to add to the post isn't actually visible to the user.  name="username" is the variable name we're posting, value is the value of that variable that we post.
		// Finally, the last line is the actual name of the button and the "submit" action.
        echo '<table>
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
                        <input type="hidden" name="sortRow" value=1>
                        <input type="hidden" name="lgdin" value=1>			
                        <input type="submit" value="Submitter">
                    </form>
                    </div>
                </th>
                <th>
                    <div id="sortButton">
                    <form action="review.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="sortRow" value=2>
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
                    <td>'.$row["journalName"].'</td>
                    <td>'.$row["submitter"].'</td>
                    <td>'.$row["submissionDateTime"].'</td>
                    <td>
                        <div id="button">
                        <form action="editComs.php" method="post">
                            <input type="hidden" name="username" value='.$username.'>
                            <input type="hidden" name="lgdin" value=1>
                            <input type="hidden" name="fname" value='.$row["journalName"].'>
                            <input type="submit" value="Submit Comments">
                        </form>
                        </div>
                    </td>
                    <td>
                        <div id="button">
                        <form action="review.php" method="post">
                            <input type="hidden" name="username" value='.$username.'>
                            <input type="hidden" name="lgdin" value=1>
                            <input type="hidden" name="sortRow" value='.$sortRow.'>
                            <input type="hidden" name="fileName" value='.$row["location"].'>
                            <input type="submit" value="Download Journal">
                        </form>
                        </div>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
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