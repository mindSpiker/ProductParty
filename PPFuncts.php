<?php

// force strong typing
ini_set('display_errors',1);
error_reporting(E_ALL);

//////////
// data file names and fields
define("PP_ID", 0);

define("PP_FN_PRODUCT", "../dfProduct.txt");
define("PP_P_PHOTO_FN", 1);
define("PP_P_PRICE", 2);
define("PP_P_TYPE", 3);
define("PP_P_LETTER", 4);

define("PP_FN_TYPE", "dfType.txt");
define("PP_T_NAME", 1);

define("PP_FN_USER", "../dfUser.txt");
define("PP_U_GENDER", 1);
define("PP_U_FIRST_NAME", 2);
define("PP_U_LAST_NAME", 3);
define("PP_U_EXPERTISE", 4);

define("PP_FN_RATING", "../dfRating.txt");
define("PP_R_USER_ID", 1);
define("PP_R_PRODUCT_ID", 2);
define("PP_R_RATING", 3);
define("PP_R_TYPE", 4);
define("PP_R_PRICE", 5);

define("PP_RANK_NAME", 1);
define("PP_RANK_VALUE_TYPE", 2);
define("PP_RANK_VALUE_PRICE", 3);
define("PP_RANK_N", 4);
define("PP_RANK_VALUE_OVERALL", 5);

// text constants
define("PP_PRODUCT", "Beer");
define("PP_EVENT_NAME", "John's House Warming Party");
define("PP_RANKS_NEEDED_TO_REVEAL", 0);
define("PP_REFRESH_SECONDS", 240);

/**
 * @param string $fileName
 * @return array:array: of rows and fields from the file
 */
function ppFileToArray($fileName) {
	$outArray = array();
	if (file_exists($fileName)) {
		$fileS = file_get_contents($fileName);
		$lines = explode("\n", $fileS);
		foreach ($lines as $i => $line) {
			if (strlen($line) > 0) {
				$outArray[$i] = explode("~", $line);
			}
		}
	}
	return $outArray;
}

/**
 * @param array $row
 * @param string $fileName
 * @return number id for this record
 */
function ppSaveRowToFile($row, $fileName) {
	// get the next ID
	$row[PP_ID] = ppGetNextId($fileName);
	//var_dump($row);
	$saveValue = "";
	foreach ($row as $i => $value) {
		if ($i > 0) {
			$saveValue .= "~";
		}
		$preparedValue = $value;
		if (is_string($preparedValue)) {
			$preparedValue = trim(str_replace("~", "", $preparedValue));
		}
		$saveValue .= $preparedValue;
	}
	//echo "[saveValue=".$saveValue."]";
	if (count($saveValue) > 0) {
		$fh = fopen($fileName, 'a');
		fputs($fh, $saveValue."\n");
		fclose($fh);
	}
	return $row[0];
}

function ppRewriteFile($data, $fileName) {
	$lines = array();
	foreach ($data as $i => $line) {
		$lines[$i] = implode("~", $line);
	}
	$allLines = implode("\n", $lines);
	file_put_contents($fileName, $allLines);
}

/**
 * @param string $fileName
 * @return number id for next record
 */
function ppGetNextId($fileName) {
	// start id at 1
	$newId = 1;
	
	// look for id in id file
	$idFileName = $fileName.".id";
	if (file_exists($idFileName)) {
		$lastId = file_get_contents($idFileName);
		$newId = $lastId + 1;
	} else {
		
		// cant find the id file so search for ids in the data file ust in case the id file got deleted
		$fileRows = ppFileToArray($fileName);
		foreach ($fileRows as $i =>$row) {
			if ($newId <= $row[0]) {
				$newId = $row[0] + 1;
			}
		}
	}
	
	// save the new id to the id file
	file_put_contents($idFileName, "".$newId);
	return $newId;
}

/**
 * @param number $id
 * @param string $fileName
 * @return array:boolean
 */
function ppGetRecordWithFileName($id, $fileName) {
	$rows = ppFileToArray($fileName);
	return ppGetRecordWithRows($id, $rows);
}

/**
 * @param number $id
 * @param string $fileName
 * @return array:boolean
 */
function ppGetRecordWithRows($id, &$rows) {
	foreach ($rows as $i => $row) {
		if ($row[PP_ID] == $id) {
			return $row;
		}
	}
	return false;
}

/**
 * @param number $id
 * @param string $fileName
 * @return number:boolean
 */
function ppGetRecordIndexWithRows($id, &$rows) {
	foreach ($rows as $i => $row) {
		if ($row[PP_ID] == $id) {
			return $i;
		}
	}
	return -1;
}

////////////////////
// Ranking 
////////////////////

function ppGetRanks($useUser) {
	$outRanks = false;
	$users = ppFileToArray(PP_FN_USER);
	$products = ppFileToArray(PP_FN_PRODUCT);
	$ratings = ppFileToArray(PP_FN_RATING);
	if (count($users) > 0 && count($products) > 0 && count($ratings) > 0) {
		$outRanks = array();
		foreach ($ratings as $i => $rating) {
			$user = ppGetRecordWithRows($rating[PP_R_USER_ID], $users);
			$product = ppGetRecordWithRows($rating[PP_R_PRODUCT_ID], $products);
			// skip records that dont have a user and a product
			if ($user && $product) {
				$rankRowIdx = false;
				if ($useUser) {
					$rankRowIdx = ppGetRecordIndexWithRows($rating[PP_R_USER_ID], $outRanks);
				} else {
					$rankRowIdx = ppGetRecordIndexWithRows($rating[PP_R_PRODUCT_ID], $outRanks);
					//echo "<p>ratingProductId=".$rating[PP_R_PRODUCT_ID]." rankRowIdx=".$rankRowIdx." var_dump(ranks)=[";var_dump($outRanks);echo "]</p>";
				}
				//echo "[1]";
				if ($rankRowIdx == -1) {
					//echo "[2]";
					// make a new rank row
					$rankRow = array();
					if ($useUser) {
						$rankRow[PP_ID] = $rating[PP_R_USER_ID];
						$rankRow[PP_RANK_NAME] = $user[PP_U_FIRST_NAME]." ".$user[PP_U_LAST_NAME];
					} else {
						$rankRow[PP_ID] = $rating[PP_R_PRODUCT_ID];
						$rankRow[PP_RANK_NAME] = $product[PP_P_LETTER];
					}
					$rankRow[PP_RANK_VALUE_PRICE] = 0;
					$rankRow[PP_RANK_VALUE_TYPE] = 0;
					$rankRow[PP_RANK_N] = 0;
					$rankRow[PP_RANK_VALUE_OVERALL] = 0;
					$rankRowIdx = count($outRanks);
					$outRanks[$rankRowIdx] = $rankRow;
				}
				// add values for this ranking
				//echo "[3]";
				if ($useUser) {
					$outRanks[$rankRowIdx][PP_RANK_VALUE_PRICE] += abs($product[PP_P_PRICE] - $rating[PP_R_PRICE]);
				} else {
					$outRanks[$rankRowIdx][PP_RANK_VALUE_PRICE] += $rating[PP_R_PRICE];
				}
				if ($product[PP_P_TYPE] == $rating[PP_R_TYPE]) {
					$outRanks[$rankRowIdx][PP_RANK_VALUE_TYPE]++;
				}
				//echo "<p>productId=".$rating[PP_R_PRODUCT_ID]." rating=".$rating[PP_R_RATING]." rankRowIdx=".$rankRowIdx."</p>";
				$outRanks[$rankRowIdx][PP_RANK_VALUE_OVERALL] += $rating[PP_R_RATING];
				$outRanks[$rankRowIdx][PP_RANK_N]++;
			}
		}
		
		if (count($outRanks) > 0) {
			// turn totals into averages
			foreach ($outRanks as $i => $v) {
				$outRanks[$i][PP_RANK_VALUE_PRICE] /= $outRanks[$i][PP_RANK_N];
				$outRanks[$i][PP_RANK_VALUE_TYPE] /= $outRanks[$i][PP_RANK_N];
				$outRanks[$i][PP_RANK_VALUE_OVERALL] /= $outRanks[$i][PP_RANK_N];
			}
		} else {
			$outRanks = false;
		}
	}
	return $outRanks;
}

function ppSortRanks($ranks, $sortField) {
	$outRanks = array();
	//echo "<p>sortField=".$sortField."</p>";
	
 	while(count($ranks) > 0) {
 		//echo "<p>count(ranks)=".count($ranks)."</p>";
 		//echo "<p>In while loop</p>";
 		//exit;
		$highestValue = 0;
		$highestValueIndex = -1;
		foreach ($ranks as $i => $row) {
			// default highest value to first index
			if ($highestValueIndex == -1) {
				$highestValueIndex = $i;	
			}
			//echo "<p>i=".$i." sortField=".$sortField."</p>";
			//echo "<p>var_dump(ranks)=[";var_dump($ranks);echo "]</p>";
			if ($row[$sortField] > $highestValue) {
				$highestValue = $row[$sortField];
				$highestValueIndex = $i;
			}
		}
		if ($highestValueIndex == -1) {
			echo "<p>Error in PPFuncts->ppSortRanks() highestValueIndex was not set</p>";
			exit;
		}
		$outRanks[count($outRanks)] = $ranks[$highestValueIndex];
		unset($ranks[$highestValueIndex]);
		
	}
	return $outRanks;
}

function ppPercent($number){
	return "".round($number * 100).'%';
}

function ppDollar($number) {
	setlocale(LC_MONETARY, 'en_US');
	if (is_double($number)) {
		return money_format('%(#10n', $number);
	} else {
		return $number;
	}
}

////////////////////
// Image file upload
////////////////////

/**
 * @param string $saveName
 * @throws RuntimeException
 * @return mixed string or false saved full file name or false if failed
 */
function saveUpFile($saveName) {
	$log = new MSLog("upload_logs", MSLog::DEBUG_EXIT);
	try {

		// Undefined | Multiple Files | $_FILES Corruption Attack
		// If this request falls under any of them, treat it invalid.
		if ( !isset($_FILES['upFile']['error']) ) {
			if (is_array($_FILES['upFile']['error']) || $_FILES['upFile']['error'] != 0) {
				$log->logError('Invalid Parameters exception thrown');
				throw new RuntimeException('Invalid parameters.');
			}
		}
		$log->logInfo("IP=".$_SERVER['REMOTE_ADDR'].", filename=".$_FILES['upFile']['tmp_name']." size=".$_FILES['upFile']['size']);

		// Check $_FILES['upfile']['error'] value.
		switch ($_FILES['upFile']['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				$log->logError('No file sent exception thrown');
				throw new RuntimeException('No file sent.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$log->logError('Exceeded filesize limit exception thrown');
				throw new RuntimeException('Exceeded filesize limit.');
			default:
				$log->logError('Unknown errors exception thrown');
				throw new RuntimeException('Unknown errors.');
		}

		// You should also check filesize here.
		if ($_FILES['upFile']['size'] > 100000000) {
			$log->logError('Exceeded filesize limit of 100MB');
			throw new RuntimeException('Exceeded filesize limit.');
		}

		// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		// Check MIME Type by yourself.
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($finfo, $_FILES['upFile']['tmp_name']);
		finfo_close($finfo);
		$haystack = array(
				'jpg' => 'image/jpg',
				'jpeg' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'bmp' => 'image/bmp' );
		$ext = array_search($mimeType, $haystack);
		$parts = explode("/", $mimeType);
		echo "<p>File Type=".$parts[1]."</p>";
		if ($ext === false) {
			$log->logError('Invalid file format exception thrown');
			//throw new RuntimeException('Invalid file format.');
			$ext = "unknown";
		}

		$newFileName = $saveName.".".$ext;
		if (!move_uploaded_file($_FILES['upFile']['tmp_name'], $newFileName)) {
			$log->logError("Failed to move uploaded file exception thrown.");
			throw new RuntimeException('Failed to move uploaded file.');
		}

		$log->logInfo("File saved successfully. Mime Type=".$mimeType." savefileName=".$newFileName);

		return $newFileName;
	} catch (RuntimeException $e) {
		$log->logFatal("Exception caught:".$e->getMessage());
		echo "<p>".$e->getMessage()."</p>";

		//echo "<h3>Oops something went wrong with file upload!!!!";
		//echo "<p>You might again or try using a different device.";
		return false;
	}
}


// 		$fh = fopen($fileName, 'r');
// 		$curRow = fgets($fh);
// 		$rowNum = 0;
// 		while ($curRow) {
// 			//echo "XXXXcurRow=[".$curRow."]XXXX";
// 			$curRow = trim($curRow);
// 			$parts = explode("~", $curRow);
// 			$outArray[$rowNum] = $parts;
// 			$curRow = fgets($fh);
// 			$rowNum++;
// 		}
// 		fclose($fh);















