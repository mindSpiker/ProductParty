<?php 
require_once 'PPFuncts.php';

function echoSaveSettings() {
	$eventName = trim($_POST['eventName']);
	$productName = trim($_POST['productName']);
	$ranksNeeded = trim($_POST['ranksNeeded']);
	$oldAdminPW = trim($_POST['oldAdminPW']);
	$newAdminPW = trim($_POST['newAdminPW']);
	
	$result = ppSaveSettings($eventName, $productName, $ranksNeeded, $oldAdminPW, $newAdminPW);
	if ($result === true) {
	    echo "<br />Settings saved!<br />";
	} else {
	    echo "Oops... suptum be wrong.<br />".$result."<br /><a href='Settings.php'>Try Again</a>";
	}
	
		// make a form for them to go to the ratings page with name as a field
		?>
	<p>
		all done.
	</p>
		<?php 
}

?><!DOCTYPE:HTML>
<Html>
<head>
<style type="text/css">
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<center>
<?php
echoSaveSettings();
?>
</center>
</body>
</Html>
