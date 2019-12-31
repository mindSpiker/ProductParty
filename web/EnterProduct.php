<?php
require_once 'PPFuncts.php';

function echoTypesAsOptions() {
	$types = ppFileToArray(PP_FN_TYPE);
	foreach ($types as $i => $type) {
		$displayName = trim($type[PP_T_NAME]);
		echo '<option value="'.$displayName.'">'.$displayName.'</option>'."\n";
	}
}

?><!DOCTYPE HTML>
<Html>
<head>
<link rel="stylesheet" href="css/styles.css">
<style type="text/css">
body {
    text-align:center;
    font-family: Arial, Helvetica, sans-serif;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php ppCheckRefreshToAdmin();?>
</head>
<body>
	<center>
	<h3>Enter Product</h3>
	<form method="post" action="SaveProduct.php" enctype="multipart/form-data">
		
		<p>Letter(s)<input type="text" name="letters"> </p>
		<p>$<input type="text" name="price"> </p>
		<p>
			<?php echo PP_PRODUCT?> Type:<br>
			<select name="type">
				<?php echoTypesAsOptions();?>
			</select>
		<p>
		<p><input name="upFile" type="file" accept="image/*" capture></input></p>
		<input type="submit" name="Submit Rating" value="submit">
	</form>
	</div>	
	</center>
</body>
</Html>
