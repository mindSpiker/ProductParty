<?php 
require_once 'PPFuncts.php';
?><!DOCTYPE:HTML>
<html>
<head>
<title>Name Entry Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
</style>
</head>
<body>
<center>
<p><strong><?php echo PP_EVENT_NAME;?> w viewport</strong></p>
<form method="post" action="SaveName.php">
First Name: <input type="text" size="12" maxlength="36" name="fName"><p />
Last Name: <input type="text" size="12" maxlength="36" name="lName"><p />
Gender: <p />
Male: <input checked type="radio" value="Male" name="gender"><br />
Female: <input type="radio" value="Female" name="gender"><p />
<!-- Precieved Expertise:<br>
<small>(10 being best)</small><br>
<select name="expertise">
<option value="10">10</option>
<option value="9">9</option>
<option value="8">8</option>
<option value="7">7</option>
<option value="6">6</option>
<option selected value="5">5</option>
<option value="4">4</option>
<option value="3">3</option>
<option value="2">2</option>
<option value="1">1</option>
<option value="0">0</option>
</select><p>
 -->
<input type="submit" value="Start Judging" name="submit">
</form>
</center>
</body>
</html>