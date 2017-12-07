<?php
header('Content-Type: application/json');

include 'config.php';
include 'functions.php';
$lookup = getCurlData($config['apiUrl']);
$obj = json_decode($lookup);
$rate = $obj[0]->price;
$rate = round($rate, 2);
$timestamp = $obj[0]->time;

// 	DO NOT EDIT

$stmt = $conn->prepare('INSERT INTO rates (`rate`) VALUES (?)');
$stmt->bind_param("s", $rate);
$stmt->execute();
$lastID = $stmt->insert_id;
$stmt->close();

$lastID = ($lastID - 1);

$stmt2 = $conn->prepare("SELECT rate FROM rates WHERE `id` = ? ORDER BY `id` DESC LIMIT 1");
$stmt2->bind_param("i", $lastID);
$stmt2->execute();
$stmt2->bind_result($row);
while ($stmt2->fetch()) {
  $previousRate = $row;
}

if($previousRate < $rate) {
	$embedColor = $config['increaseColor'];
	$iconUrl = $config['increaseIcon'];
	$fluctuation = "**increased**";
} else {
	$embedColor = $config['decreaseColor'];
	$iconUrl = $config['decreaseIcon'];
	$fluctuation = "**decreased**";
}
$difference = $previousRate - $rate;
$difference = str_replace('-', '', $difference);

$url = "";
$title = "";
$authorName = "Current: $".$rate;
$description = "The current rate has $fluctuation by $". round($difference, 2);
$botName = $config['botName'];
$botAvatar = $config['botAvatar'];
$footerIcon = $config['botFooter'];
$content = "";

$footerText = "Coming from GDAX.";

webhook($config['webhookUrl'], $url, $title, $description, $embedColor, $timestamp, $footerIcon, $footerText, $authorName, $iconUrl, $botName, $botAvatar, $content);
