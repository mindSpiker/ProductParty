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
				<th>Beers<br />Rated</th>
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
			echo "\t\t\t\t<td>".ppDollar($rankPrice[PP_RANK_VALUE_PRICE])."</td>\n";
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
				<th>Beers<br />Rated</th>
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
<meta http-equiv="refresh" content="<?php echo PP_REFRESH_SECONDS;?>; http://burgerbot.com/Leaderboard.php">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	<h3>Leaderboard With Viewport</h3>
	<p>start at<br /><strong>http://burgerbot.com/start.php</strong><p>
	<?php echoLeaderboardRankings(); ?>
	</center>
</body>
</Html>