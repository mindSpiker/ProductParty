<?php

require_once 'PPFuncts.php';

function echoReveal() {
	?>
	<table border="1">
		<tr>
			<th>ID</th>	
			<th>Image</th>
			<th>Average<br />Rating</th>
			<th>Price</th>
			<th>Average Price<br />Guess</th>
			<th>Type</th>
			<th>Average Type<br />Accuracy</th>
		</tr>
	<?php
	$products = ppFileToArray(PP_FN_PRODUCT);
	$ranks = ppGetRanks(false);
	//echo "<p>var_dump(ranks)=[";var_dump($ranks);echo "]</p>";
	
	foreach ($products as $i => $product) {
		//echo "<p>products[PP_ID]=".$product[PP_ID]."<p>";
		$rank = ppGetRecordWithRows($product[PP_ID], $ranks);
		//echo "<p>var_dump(rank)=";var_dump($rank); echo "<p>";
		echo "<tr>";
		echo "<td>".$product[PP_P_LETTER]."</td>";
		// image
		if ($rank[PP_RANK_N] >= PP_RANKS_NEEDED_TO_REVEAL) {
			echo "<td><img src=\"".$product[PP_P_PHOTO_FN]."\" width=\"90\" height=\"160\" /></td>";	
		} else {
			echo "<td>???</td>";
		}
		// rating
		echo "<td>".round($rank[PP_RANK_VALUE_OVERALL], 2)."</td>";
		// price
		if ($rank[PP_RANK_N] >= PP_RANKS_NEEDED_TO_REVEAL) {
			echo "<td>".ppDollar($product[PP_P_PRICE])."</td>";
		} else {
			echo "<td>???</td>";
		}
		// rank price
		echo "<td>".ppDollar($rank[PP_RANK_VALUE_PRICE])."</td>";
		// type
		if ($rank[PP_RANK_N] >= PP_RANKS_NEEDED_TO_REVEAL) {
			echo "<td>".$product[PP_P_TYPE]."</td>";
		} else {
			echo "<td>???</td>";
		}
		echo "<td>".ppPercent($rank[PP_RANK_VALUE_TYPE])."</td>";
		echo "</tr>";	
	}
	echo "</table>";
}



?><!DOCTYPE HTML>
<Html>
<head>
<meta http-equiv="refresh" content="<?php echo PP_REFRESH_SECONDS;?>; http://burgerbot.com/Reveal.php">
<style type="text/css">
body, table, th, td {
	text-align:center;
}
th, td {
	padding-left:10pt;
	padding-right:10pt;
}
table {
		border-collapse: collapse;
}
</style>
</head>
<body>
	<center>
	<h3><?php echo PP_PRODUCT?> Rankings</h3>
	<p>start at<br /><strong>http://burgerbot.com/start.php</strong><p>
	<?php echoReveal();?>
	</center>
</body>
</Html>