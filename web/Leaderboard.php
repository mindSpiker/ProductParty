<?php

require_once 'PPFuncts.php';

function echoLeaderboardRankings() {
	$ranks = ppGetRanks(true);
	if ($ranks != false) {
		?>
	<div style="width:1000px;margin-left: auto;margin-right:auto;">
		<table border="1" style="float: left; width:47.5%; margin-right:5%;">
			<tr>
				<th>Name</th>
				<th>Rank</th>
				<th>Avgerage Price<br />Difference</th>
				<th><?php echo PP_PRODUCT;?>s<br />Rated</th>
			</tr>
		<?php
		$ranksPrice = ppSortRanks($ranks, PP_RANK_VALUE_PRICE);
		$ranksPrice = array_reverse($ranksPrice, true);
		$count = 0;
		foreach ($ranksPrice as $i => $rankPrice) {
			$count++;
			echo "\t\t\t<tr>\n";
			echo "\t\t\t\t<td>".$rankPrice[PP_RANK_NAME]."</td>\n";
			echo "\t\t\t\t<td>".$count."</td>\n";
			if ($rankPrice[PP_RANK_N] > 2) {
                echo "\t\t\t\t<td>".ppDollar($rankPrice[PP_RANK_VALUE_PRICE])."</td>\n";
			} else {
			    echo "\t\t\t\t<td>---</td>\n";
			}
			echo "\t\t\t\t<td>".$rankPrice[PP_RANK_N]."</td>\n";
			echo "\t\t\t</tr>\n";
		}	
		?>
		</table>
		<table border="1" style="float: left; width:47.5%">
			<tr>
				<th>Name</th>
				<th>Rank</th>
				<th>Average Type<br />Accuracy</th>
				<th><?php echo PP_PRODUCT;?>s<br />Rated</th>
			</tr>
		<?php
		$ranksType = ppSortRanks($ranks, PP_RANK_VALUE_TYPE);
		$count = 0;
		foreach ($ranksType as $i => $rankType) {
			$count++;
			echo "\t\t\t<tr>\n";
			echo "\t\t\t\t<td>".$rankType[PP_RANK_NAME]."</td>\n";
			echo "\t\t\t\t<td>".$count."</td>\n";
			echo "\t\t\t\t<td>".ppPercent($rankType[PP_RANK_VALUE_TYPE])."</td>\n";
			echo "\t\t\t\t<td>".$rankType[PP_RANK_N]."</td>\n";
			echo "\t\t\t</tr>";
		}	
		?>
		</table>
	</div>
		<?php 
	} else {
		echo "<p>No Ranks Yet</p>";
	}
}

?><!DOCTYPE HTML>
<Html>
<head>
<meta http-equiv="refresh" content="<?php echo PP_REFRESH_SECONDS;?>; Leaderboard.php">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/styles.css">
<style type="text/css">
</style>
</head>
<body>
	<img style="
	   position: fixed;
	   bottom: 0;right: 0;
	   opacity: 30%;
	   width: 100%;
	   z-index: -1" src="Images/background.jpeg"/>
	<center>
	<h3>Leaderboard</h3>
	<p>start at<br /><strong>http://burgerbot.com/start.php</strong><p>
	<?php echoLeaderboardRankings(); ?>
	</center>
</body>
</Html>
