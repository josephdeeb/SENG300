<!--

viewUnassignedJournals.php

----------------------------------------------------------------------------------------------------------------------------------------------------

This is a page for the editor so that he can view unassigned journal.

----------------------------------------------------------------------------------------------------------------------------------------------------
Post inputs:
	username	- username of logged in user
	lgdin		- posted when logged in user is returning to main menu
    sortRow     - determines how the journals are sorted

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
    
    $username = $_POST["username"];
    
    // Create connection
	$con = mysqli_connect("localhost","seng300","seng300Spr2019", "seng300");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		die();
	}
	// end verify
    
    $query = "SELECT * FROM journals WHERE NOT EXISTS (SELECT * FROM reviewers WHERE name = journalName)";
    
    // Check if sortRow was posted
    if (isset($_POST["sortRow"])) {
        $sortRow = $_POST["sortRow"];
    } else {
        $sortRow = 0;
    }
    
    // Add to the end of the query so we order by the appropriate column
    if ($sortRow == 0) {
        $query = $query." ORDER BY name";
    } else if ($sortRow == 1) {
        $query = $query." ORDER BY submitter";
    } else if ($sortRow == 2) {
        $query = $query." ORDER BY submissionDateTime";
    }
    
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo '<p>Unassigned Journals</p>';
        echo '<table>
                <tr>
                <th>
                    <div id="sortButton">
                    <form action="viewUnassignedJournals.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="submit" value="Journal Name">
                    </form>
                    </div>
                </th>
                <th>
                    <div id="sortButton">
                    <form action="viewUnassignedJournals.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="hidden" name="sortRow" value=1>
                        <input type="submit" value="Submitter">
                    </form>
                    </div>
                </th>
                <th>
                    <div id="sortButton">
                    <form action="viewUnassignedJournals.php" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="hidden" name="sortRow" value=2>
                        <input type="submit" value="Submission Date">
                    </form>
                    </div>
                </th>
                <th>
                </th>
                <th>
                </th>
                </tr>';
        
        // Now we're going to start pulling rows from the database
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>
                    <td>'.$row["name"].'</td>
                    <td>'.$row["submitter"].'</td>
                    <td>'.$row["submissionDateTime"].'</td>
                    <td>
                        <div id="button">
                        <form action="assignReviewers.php" method="post">
                            <input type="hidden" name="username" value='.$username.'>
                            <input type="hidden" name="lgdin" value=1>
                            <input type="hidden" name="fname" value='.$row["name"].'>
                            <input type="submit" value="Assign Reviewers">
                        </form>
                        </div>
                    </td>
                    </tr>';
        }
        echo '</table>';
    }
    
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