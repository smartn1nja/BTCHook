<?php
header('Content-Type: application/json');

include 'config.php';
$lookup = getCurlData($apiURL);
$obj = json_decode($lookup);
$rate = $obj->bpi->USD->rate;
$rate = str_replace(',', '', $rate);
$rate = round($rate, 2);
$timestamp = $obj->time->updateduk;

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
	$embedColor = "#43b581";
	$iconUrl = "https://cdn.discordapp.com/emojis/347774024425799680.png";
	$fluctuation = "**increased**";
} else {
	$embedColor = "#f04947";
	$iconUrl = "https://cdn.discordapp.com/emojis/347774023997849612.png";
	$fluctuation = "**decreased**";
}
$difference = $previousRate - $rate;
$difference = str_replace('-', '', $difference);

$url = " ";
$title = "";
$authorName = "Current: $".$rate;
$description = "The current rate has $fluctuation by $". $difference;
$botName = "MEGALUL";
$botAvatar = "https://cdn.discordapp.com/attachments/236152872314732544/385169469317578762/megalul.jpg";
$footerIcon = "https://cdn.discordapp.com/attachments/236152872314732544/385169469317578762/megalul.jpg";
$content = "";

$footerText = "Updated: $timestamp";

webhook($webhook, $url, $title, $description, $embedColor, $footerIcon, $footerText, $authorName, $iconUrl, $botName, $botAvatar, $content);
