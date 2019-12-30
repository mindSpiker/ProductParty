<?php

require_once 'PPFuncts.php';

function echoReveal() {
	?>
	<table id="rankTable" border="1">
		<tr>
			<th><button onclick="sortTable(0)">Show<br /> </button></th>
			<th><button onclick="sortTable(1)">ID</button></th>	
			<th>Image</th>
			<th><button onclick="sortTable(3)">Average<br />Rating</button></th>
			<th><button onclick="sortTable(4)">Price</button></th>
			<th><button onclick="sortTable(5)">Average Price<br />Guess</button></th>
			<th>Type</th>
			<th><button onclick="sortTable(7)">Average Type<br />Accuracy</button></th>
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
			echo "<td>".ppDollar($product[PP_P_PRICE])."</td>";
		} else {
			echo "<td>???</td>";
		}
		// rank price
		echo "<td width=\"90\">".ppDollar($rank[PP_RANK_VALUE_PRICE])."<br /><br />";
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
	font-family: Arial, Helvetica, sans-serif;
}
th, td {
	padding-left:10pt;
	padding-right:10pt;
}
table {
		border-collapse: collapse;
}
button {
  background-color: white;
  border: none;
  color: black;
  text-align: center;
  text-decoration: bold;
  font-size: 16px;
  font-family: Arial, Helvetica, sans-serif;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
<script>
var lastColIdx = 1;
var sortAcending = true;
function sortTable(colIdx) {
	if (lastColIdx == colIdx) {
		sortAcending = !sortAcending;
	}
	lastColIdx = colIdx;
	var table, rows, switching, i, x, y, shouldSwitch;
  	table = document.getElementById("rankTable");
  	switching = true;
  	/* Make a loop that will continue until no switching has been done: */
  	while (switching) {
    	// Start by saying: no switching is done:
    	switching = false;
    	rows = table.rows;
    	/* Loop through all table rows (except the first, which contains table headers): */
    	for (i = 1; i < (rows.length - 1); i++) {
      		// Start by saying there should be no switching:
      		shouldSwitch = false;
      		/* Get the two elements you want to compare,
      		one from current row and one from the next: */
      		x = rows[i].getElementsByTagName("TD")[colIdx];
      		y = rows[i + 1].getElementsByTagName("TD")[colIdx];
      		// Check if the two rows should switch place:
      		if (sortAcending) {
      			if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        		// If so, mark as a switch and break the loop:
        			shouldSwitch = true;
        			break;
      			}
      		} else {
      			if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            		// If so, mark as a switch and break the loop:
            		shouldSwitch = true;
            		break;
          		}
      		}
    	}
    	if (shouldSwitch) {
      		/* If a switch has been marked, make the switch
      		and mark that a switch has been done: */
      		rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      		switching = true;
    	}
  	}
}
</script>
</head>
<body>
	<center>
	<h3><?php echo PP_PRODUCT?> Rankings</h3>
	<p>start at<br /><strong>http://burgerbot.com/start.php</strong><p>
	<?php echoReveal();?>
	</center>
</body>
</Html>