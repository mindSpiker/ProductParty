<?php 
require_once 'PPFuncts.php';
require_once 'MSLog.php';

function echoSaveProduct() {
	
	$letters = isset($_POST['letters']) ? preg_replace('/[^A-Z0-9-_\.]/','',strtoupper(trim($_POST['letters']))) : false;
	$saveName = "Images/".$letters;
	$photoFN = false;
	if (isset($_FILES['upFile'])) {
		$photoFN = saveUpFile($saveName);
	}
	$price = isset($_POST['price']) ? trim($_POST['price']) : false;
	$type = isset($_POST['type']) ? trim($_POST['type']) : false;
	
	//echo "[strlen(_POST['gender'])=".strlen($_POST['gender'])." strlen(fName)=".strlen($fName)." strlen(lName)=".strlen($lName)."]";
	if ($letters) {
		// search for letters
		$letterFound = false;
		$letterFoundRow = -1;
		$products = ppFileToArray(PP_FN_PRODUCT);
		foreach ($products as $i => $product) {
			if (strtolower($product[PP_P_LETTER]) == strtolower($letters)) {
				$letterFound = true;
				$letterFoundRow = $i;
				break;
			}
		}
		
		$row = array();
		if ($letterFound == true) {
			$row = $products[$letterFoundRow];
		} else {
			$row[PP_ID] = 0;
		}
		
		if ($photoFN) {
			$row[PP_P_PHOTO_FN] = $photoFN;
		}
		if ($price) {
			$row[PP_P_PRICE] = $price;
		}
		if ($type) {
			$row[PP_P_TYPE] = $type;
		}
		$row[PP_P_LETTER] = $letters;
		
		if ($letterFound) {
			$products[$letterFoundRow] = $row;
			ppRewriteFile($products, PP_FN_PRODUCT);
		} else {
			ppSaveRowToFile($row, PP_FN_PRODUCT);
			echo "<p>".PP_PRODUCT." ".$letters." was added.</p>";
		}

		// make a form to go back to enter another product
		?>
		<p><a href="EnterProduct.php">Enter Another <?php echo PP_PRODUCT;?></a></p>
		<?php 
		
	} else {
		echo "<p>Oops... suptum be wrong<br /><a href='EnterProduct.php'>Try Again</a></p>";
	}
}

?><!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
body table tr td th { 
	text-align:"center";
}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</style>
</head>
<body>
<center>
<h3>Save Product Page</h3>
<?php echoSaveProduct();?>
</center>
</body>
</html>
