<?php
//get ranking data
$mode = 'daily'; //daily,weekly,monthly,male,female,original,daily_r18,weekly_r18,male_r18,female_r18
$date = 20160714; //
$pageNumber = 1; //start from 1
$jsonURL = "http://www.pixiv.net/ranking.php?mode=".
    $mode."&content=illust&date=".$date."&p=".$pageNumber.
    "&format=json";
$pixivDailyRank = file_get_contents($jsonURL);
$json_a = json_decode($pixivDailyRank, true);
var_dump($json_a);
?>
