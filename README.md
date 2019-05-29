1. Install XAMPP with default settings

2. open "xampp-control.exe" app

3. click "Start" beside both "Apache" and "MySQL"

4. click "Admin" beside "MySQL". this will open mySQL in a browser

5. On the left hand side, create a new database, and set the encoding to utf8_general_ci, then click create

6. go to the "SQL" tab at the top and paste this:

	GRANT ALL PRIVILEGES ON \*.\* TO 'seng300'@'localhost' IDENTIFIED BY PASSWORD '*FB9E62BC19D7707A2794D75F8F9FF93BBD020635' WITH GRANT OPTION;
	
   this creates the user that we will be using for access to the database.
   
7. you must pull the repository to a folder named "SENG300" in the "htdocs" folder. mine is at "C:\xampp\htdocs\SENG300"

8. go to the import tab and choose the "seng300.sql" file and click "Go" at the bottom of the page. this will upload the database information.

9. move the "index.php" file from the "seng300" folder and place it into the "htdocs" folder.

10. create a folder named "journals" in the "seng300" folder

11. create a folder named "revisions" in the "journals" folder

12. click "Admin" beside "Apache". this will open the web app
