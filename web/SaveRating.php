<?php 

require_once 'PPFuncts.php';

function echoSaveRating() {

	$userId = isset($_POST['userId']) ? trim($_POST['userId']) : -1;
	$userRec = false;
	if ($userId >= 0) {
		$userRec = ppGetRecordWithFileName($userId, PP_FN_USER);
	}
	$productId = isset($_POST['productId']) ? trim($_POST['productId']) : -1;
	$productRec = false;
	if ($productId >= 0) {
		$productRec = ppGetRecordWithFileName($productId, PP_FN_PRODUCT);
	}
	$userRating = isset($_POST['rating']) ? trim($_POST['rating']) : false;
	$price = isset($_POST['price']) ? trim($_POST['price']) : false;
	$type = isset($_POST['type']) ? trim($_POST['type']) : -1;

	//echo "[strlen(_POST['gender'])=".strlen($_POST['gender'])." strlen(fName)=".strlen($fName)." strlen(lName)=".strlen($lName)."]";
	if ($userId>=0 && $productId>=0 && $userRating && $price && $type>=0 && $userRec && $productRec && $productRec[PP_P_REVEAL] == 0) {
		// search for product and user rating
		$recordFound = false;
		$ratings = ppFileToArray(PP_FN_RATING);
		foreach ($ratings as $i => $rating) {
			if ($rating[PP_R_USER_ID] == $userId && $rating[PP_R_PRODUCT_ID] == $productId) {
				$recordFound = true;
				break;
			}
		}
		
		$displayName = $userRec[PP_U_FIRST_NAME]." ".$userRec[PP_U_LAST_NAME];
		if ($recordFound) {
			echo "<p>Dude, you must be drunk!</p>";
			echo "<p>Rating for ".PP_PRODUCT." ".$productRec[PP_P_LETTER]." by ".$displayName." already exists.<br>";
			echo "Only one rating can be recored for each person-".PP_PRODUCT." combination.<p>";
		} else {
			$row = array();
			$row[PP_ID] = 0;
			$row[PP_R_USER_ID] = $userId;
			$row[PP_R_PRODUCT_ID] = $productId;
			$row[PP_R_RATING] = $userRating;
			$row[PP_R_TYPE] = $type;
			$row[PP_R_PRICE] = $price;
			ppAddRowToFile($row, PP_FN_RATING);
			echo "<p>Rating for ".PP_PRODUCT." ".$productRec[PP_P_LETTER]." by ".$displayName." was added.</p>";
		}
		echoRankings($userId);
	} else {
		echo "<p>Oops... something is wrong<br />Use back button to go back and fix it.</p>";
		if ($userId < 0) {
			echo "<p>Can't find user</p>";
		} else {
			if ($userRec == false) {
				echo "<p>Can't find the user record for id:".$userId." type</p>";
			}
		}
		if ($productId < 0) {
			echo "<p>Can't find the ".PP_PRODUCT." to rate</p>";
		} else {
			if ($productRec == false) {
				echo "<p>Can't find the product record for id:".$productId." type</p>";
			} else if ($productRec[PP_P_REVEAL] != 0) {
			    echo "<p>Wine ".$productRec[PP_P_LETTER]." has already been revealed.<br />";
			    echo "To rate it now would cheating!</p>";
			}
		}
		if ($userRating == false) {
			echo "<p>Can't find the rating</p>";
		}
		if ($price == false) {
			echo "<p>Can't find the price estimate</p>";
		}
		if ($type < 0) {
			echo "<p>Can't find the ".PP_PRODUCT." type</p>";
		}
	}
	// make a form for them to go to the ratings page with name as a field
	?>
		<p>
			<form method="post" action="EnterRating.php">
				<input type="hidden" value="<?php echo $userId;?>" name="userId">
				<input type="submit" value="Rate more <?php echo PP_PRODUCT;?>"s name="submit">
			</form>
		</p>
	<?php 
}

function echoRankings($userId) {
	$ranks = ppGetRanks(true);
	if ($ranks != false) {
		$ranksPrice = ppSortRanks($ranks, PP_RANK_VALUE_PRICE);
		$rankPriceCount = 0;
		$ranksPrice = array_reverse($ranksPrice, true);
		foreach ($ranksPrice as $i => $rankPrice) {
			$rankPriceCount++;
			if ($userId == $rankPrice[PP_ID]) {
				echo "<p> Price Rank: ".$rankPriceCount." out of ".count($ranksPrice)."</p>";
				break;
			}
		}
		
		$ranksType = ppSortRanks($ranks, PP_RANK_VALUE_TYPE);
		$rankTypeCounter = 0;
		foreach ($ranksType as $i => $rankType) {
			$rankTypeCounter++;
			if ($userId == $rankType[PP_ID]) {
				echo "<p> Type Rank: ".$rankTypeCounter." out of ".count($ranksType)."</p>";
				break;
			}
		}
	} else {
		echo "<p>No Ranks Yet</p>";
	}
}

?><!DOCTYPE HTML>
<Html>
<head>
<style type="text/css">
body {
    text-align:center;
    font-family: Arial, Helvetica, sans-serif;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<center>
	<h3>Save Rating</h3>
	<?php echoSaveRating();?>
	</center>
</body>
</html>
