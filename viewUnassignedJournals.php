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
    
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo '<p>Unassigned Journals</p>';
        echo '<table>
                <tr>
                <th>
                    <div id="sortButton">
                    <form action="viewUnassignedJournals" method="post">
                        <input type="hidden" name="username" value='.$username.'>
                        <input type="hidden" name="lgdin" value=1>
                        <input type="submit" value="Journal Name"';
        
        echo '</table>';
    }
    
?>
</body>
</html>