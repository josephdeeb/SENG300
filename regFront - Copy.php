<html>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function checkAvailability() {
	$("#loaderIcon").show();
	jQuery.ajax({
	url: "check_availability.php",
	data:'username='+$("#username").val(),
	type: "POST",
	success:function(data){
		$("#user-availability-status").html(data);
		$("#loaderIcon").hide();
	},
	error:function (){}
	});
}
</script>
<script>
function validate() {
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();
    if(password1 == password2) {
         $("#validate-status").text("Passwords Match!");
         $('#submit-button').prop('disabled', false);
    }
    else {
         $("#validate-status").text("Passwords Do Not Match!");  
         $('#submit-button').prop('disabled', true);
    }
}
    
$("#password2").keyup(validate);
</script>

<body>

<h1>New Customer Registration</h1>

<form action="cRegBack.php" method="post">
    Never Ordered Before?<br>
    Register Here<br><br>

	<div id="frmCheckUsername">
	  <label>Check Username:<br></label>
	  <input name="username" type="text" id="username" class="demoInputBox" onBlur="checkAvailability()" required>*<span id="user-availability-status"></span>    
	</div>
	<p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>

	<div class="col-sm-4">
		<div class="form-group"><label>Password<br></label>
			<input type="password"   id="password1"   name="password" pattern=".{8,}" class="form-control input-sm" required/>
		</div>
	</div>
																  
	<div class="col-sm-4">
		<div class="form-group"><label>Confirm Password<br></label>
			<input type="password"   id="password2"   class="form-control input-sm" required/>
		</div>
		<div id="msg"></div>
	</div>
    <p id="validate-status"></p>

    Enter First Name:<br>
    <input type="text" name="fname" required>*<br>

    Enter Last Name:<br>
    <input type="text" name="lname" required>*<br>

	Choose What Type of User You Are:<br>
    <input type="radio" name="type" value=1 required> Submitter<br>
    <input type="radio" name="type" value=2> Reviewer<br>

    <input type="reset">
    <input id="submit-button" type="submit" value="Submit">

</form>
<form action="index.php" method="post">
	<input type="submit" value="Return to Main Menu">
</form>	

</body>
</html>
