<?php 
require_once 'PPFuncts.php';
?><!DOCTYPE:HTML>
<html>
<head>
<title>Name Entry Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/styles.css">
<style type="text/css">
</style>
<?php ppCheckRefreshToAdmin();?>
</head>
<body>
<center>
<p><strong><?php echo PP_EVENT_NAME;?></strong></p>
<form method="post" action="SaveName.php">
	<p>First Name: <input type="text" size="12" maxlength="36" name="fName"></p>
	<p>Last Name: <input type="text" size="12" maxlength="36" name="lName"></p>
	<p>Gender: <br />
		Male: <input checked type="radio" value="Male" name="gender"><br />
		Female: <input type="radio" value="Female" name="gender">
	</p>
	<input type="submit" value="Start Judging" name="submit">
</form>
</center>
</body>
</html>
