<?php

require_once 'PPFuncts.php';

function echoReveal() {
	?>
	<table border="1">
		<tr>
			<th></th>
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
	
	foreach ($products as $product) {
		//echo "<p>products[PP_ID]=".$product[PP_ID]."<p>";
		$rank = false;
	    if (!empty($ranks)) {
            $rank = ppGetRecordWithRows($product[PP_ID], $ranks);
	    }
		//echo "<p>var_dump(rank)=";var_dump($rank); echo "<p>";
		echo "<tr>";
		
		// check if product need to be revealed due to the number of ranks
		checkRevealProduct($products, $product, $rank[PP_RANK_N]);
		
		if ($product[PP_P_REVEAL] == 0) {
		    echo "<td><a href=\"Reveal.php?revealId=".$product[PP_ID]."\">show</a></td>";
		} else {
		    echo "<td></td>";
		}
		echo "<td>".$product[PP_P_LETTER]."</td>";
		// image
		if ($product[PP_P_REVEAL]) {
			echo "<td><img src=\"".$product[PP_P_PHOTO_FN]."\" height=\"160\" /></td>";	
		} else {
			echo "<td>???</td>";
		}
		// rating
		echo "<td width=\"90\">".round($rank[PP_RANK_VALUE_OVERALL], 2)."<br /><br />";
		echo "Values: ".$rank[PP_RANK_VALUES_OVERALL]."</td>";
		// price
		if ($product[PP_P_REVEAL]) {
			echo "<td>$".ppDollar($product[PP_P_PRICE])."</td>";
		} else {
			echo "<td>???</td>";
		}
		// rank price
		echo "<td width=\"90\">$".ppDollar($rank[PP_RANK_VALUE_PRICE])."<br /><br />";
		echo "Values: ".$rank[PP_RANK_VALUES_PRICE]."</td>";
		// type
		if ($product[PP_P_REVEAL]) {
			echo "<td>".$product[PP_P_TYPE]."</td>";
		} else {
			echo "<td>???</td>";
		}
		echo "<td width=\"180\">".ppPercent($rank[PP_RANK_VALUE_TYPE])."<br /><br />";
		echo "Values: ".$rank[PP_RANK_VALUES_TYPE]."</td>";
		echo "</tr>";	
	}
	echo "</table>";
}

function checkRevealProduct(&$products, &$product, $ranks) {
    
    if ($product[PP_P_REVEAL] == 1) {
        return;
    }
    
    $reveal = false;
    
     // check if product need to be revealed due to the number of ranks
    if ($product[PP_P_REVEAL] == 0 && $ranks >= PP_RANKS_NEEDED_TO_REVEAL) {
        $reveal = true;
    }
    
    // check if product was marked for reveal by user click
    if (isset($_GET['revealId']) && $_GET['revealId'] == $product[PP_ID]) {
        $reveal = true;
    }
    
    if ($reveal) {
        $product[PP_P_REVEAL] = 1;
        
        // move image to web area
        $oldName = $product[PP_P_PHOTO_FN];
        $newName = str_replace("../Images/", "Images/", $oldName);
        // make directory if needed
        if (!is_dir(dirname($newName))) {
            mkdir(dirname($newName));
        }
        if (file_exists($oldName)) {
            rename($oldName, $newName);
        }
        $product[PP_P_PHOTO_FN] = $newName;
        $products[$product[PP_ID]] = $product;
        //echo "products=[".var_dump($products)."]<br />";
        pp2DArrayToFile(PP_FN_PRODUCT, $products);
    }
}

?><!DOCTYPE HTML>
<Html>
<head>
<meta http-equiv="refresh" content="<?php echo PP_REFRESH_SECONDS;?>; /Reveal.php">
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