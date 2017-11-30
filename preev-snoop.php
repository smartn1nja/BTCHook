<?php
session_start();
include 'config.php';
$lookup = getCurlData($apiURL);
$obj = json_decode($lookup);
$rate = $obj->bpi->USD->rate;
$rate = str_replace(',', '', $rate);
$rate = round($rate, 2);
$update = $obj->time->updateduk;

#	DO NOT EDIT

$stmt = $conn->prepare('INSERT INTO rates (`rate`) VALUES (?)');
$stmt->bind_param("s", $rate);
$stmt->execute();
$lastID = $stmt->insert_id;
$stmt->close();

$lastID = ($lastID - 1);

$stmt3 = $conn->prepare("SELECT rate FROM rates WHERE `id` = ? ORDER BY `id` DESC LIMIT 1");
$stmt3->bind_param("i", $lastID);
$stmt3->execute();
$stmt3->bind_result($row);
while ($stmt3->fetch()) {
  $lastRate = $row;
}

$linkUrl = "http://preev.com/btc/usd";
$linkDesc = "Last updated ".$update;

if($rate > $lastRate ){
	$linkTitle = ":arrow_up_small: Current: $". $rate;
	$embedColor = "#64dd17";
} else {
	$linkTitle = ":arrow_down_small: Current: $". $rate;
	$embedColor = "#c62828";
}
$botName = "MEGALUL";
$botAvatar = "https://i.rexsdev.com/xVQzt-DdwRM-yic17.png";
$content = null;
webhook($webhook, $linkUrl, $linkTitle, $linkDesc, $embedColor, $botName, $botAvatar, $content);
