<?php
require_once 'PPFuncts.php';

function echoUserNamesAsOptions() {
	$users = ppFileToArray(PP_FN_USER);
	foreach ($users as $i => $user) {
		$userName = $user[PP_U_FIRST_NAME]." ".$user[PP_U_LAST_NAME];
		$userId = $user[PP_ID];
		$currentUserId = 0;
		if (isset($_POST['userId'])) {
			$currentUserId = $_POST['userId'];
		}
		if ($userId == $currentUserId) {
			echo '<option selected value="'.$userId.'">'.$userName.'</option>'."\n";
		} else {
			echo '<option value="'.$userId.'">'.$userName.'</option>'."\n";
		}
	}
}

function echoProductLetterAsOptions() {
	echo "hello";
	$products = ppFileToArray(PP_FN_PRODUCT);
	echo "<p>var_dump(products)=[";var_dump($products);echo "]</p>";
	foreach ($products as $i => $product) {
		$productLetter = trim($product[PP_P_LETTER]);
		$productId = $product[PP_ID];
		echo '<option value="'.$productId.'">'.$productLetter.'</option>'."\n";
	}
}

function echoTypesAsOptions() {
	$types = ppFileToArray(PP_FN_TYPE);
	//var_dump($types);
	foreach ($types as $i => $type) {
		$displayName = trim($type[PP_T_NAME]);
		echo '<option value="'.$displayName.'">'.$displayName.'</option>'."\n";
	}
}

?><!DOCTYPE HTML>
<Html>
<head>
<style type="text/css">

</style>
</head>
<body>
	<center>
	<h3>Enter Rating sdfgdfgdsfg</h3>
	<form method="post" action="SaveRating.php">
		<p>
			Select your name from the list:<br />
			<select name="userId">
				<?php echoUserNamesAsOptions();?>
			</select>
		</p>
		<p>
			Select the beer letter from the list:<br />
			<select name="productId">
				<?php 
				echo "Hello world";
				echoProductLetterAsOptions();?>
			</select>
		</p>
		Your guess of the price per bottle:<br>
		<small>(don't enter the $ sign)</small><br>
		$<input type="text" name="price"> 
		<p>
			Overall rating:<br>
			<small>(10 being best)</small><br>
			<select name="rating">
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
			</select>
		</p>
		<p>
		<?php echo PP_PRODUCT?> Type:<br>
		<select name="type">
			<?php echoTypesAsOptions();?>
		</select>
		</p>
		<input type="submit" name="Submit Rating" value="submit">
	</form>
	</center>
</body>
</Html>