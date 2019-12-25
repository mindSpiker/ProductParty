<?php 
require_once 'PPFuncts.php';

function echoSaveName() {
	$fName = trim($_POST['fName']);
	$lName = trim($_POST['lName']);
	//echo "[strlen(_POST['gender'])=".strlen($_POST['gender'])." strlen(fName)=".strlen($fName)." strlen(lName)=".strlen($lName)."]";
	if (strlen($_POST['gender']) > 0 && (strlen($fName) > 0 || strlen($lName) > 0)) {
		// search for name
		$userId = 0;
		$nameFound = false;
		$users = ppFileToArray(PP_FN_USER);
		//var_dump($users);
		foreach ($users as $i => $user) {
			if (strtolower($user[PP_U_FIRST_NAME]) == strtolower($fName) && strtolower($user[PP_U_LAST_NAME]) == strtolower($lName)) {
				$nameFound = true;
				break;
			}
		}
	
		if ($nameFound == true) {
			echo "<h3>".$fName." ".$lName."</h3><p>Found your name.</p>";
		} else {
			$row = array();
			$row[PP_ID] = 0;
			$row[PP_U_GENDER] = $_POST['gender'];
			$row[PP_U_FIRST_NAME] = $fName;
			$row[PP_U_LAST_NAME] = $lName;
			//$row[PP_U_EXPERTISE] = $_POST['expertise'];
			$userId = ppSaveRowToFile($row, PP_FN_USER);
			echo '<p>Your name was added.</p>';
		}
		
		// make a form for them to go to the ratings page with name as a field
		?>
	<p>
		<form method="post" action="EnterRating.php">
			<input type="hidden" value="<?php echo $userId;?>" name="userId">
			<input type="submit" value="Rate the <?php echo PP_PRODUCT;?>" name="submit">
		</form>
	</p>
		<?php 
		
	} else {
		echo "Oops... suptum be wrong<br /><a href='EnterName.php'>Try Again</a>";
	}
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
echoSaveName();
?>
</center>
</body>
</Html>
