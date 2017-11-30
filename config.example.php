<?php
// GENERAL CONFIGURATION
$webhook = "";
$apiURL = "https://api.coindesk.com/v1/bpi/currentprice/USD.json";

// DATABASE CONFIGURATION
define("DBHOST", "m");
define("DBUSER", "");
define("DBPASS", "");
define("DATABS", "");

function getCurlData($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
	$curlData = curl_exec($curl);
	curl_close($curl);
	return $curlData;
}

function webhook($webhook, $url, $title, $description, $embedColor, $footerIcon, $footerText, $authorName, $iconUrl, $botName, $botAvatar, $content) {
  if(isset($embedColor)) {
    if(strpos($embedColor, "#") > -1) {
      $c=str_replace("#", "", $embedColor);
      if (!preg_match("/#([a-fA-F0-9]{3}){1,2}\b/", $c)) {
        $color = hexdec( strtolower($c) );
      }
    }
  } else {
    $color = 0;
  }

  $sys["content"] = $content;
  $sys["username"] = $botName;
  $sys["avatar_url"] = $botAvatar;
  $footer = array("icon_url" => $footerIcon, "text" => $footerText);
  $author = array("url" => "", "name" => "$authorName", "icon_url" => $iconUrl);
  $embed = array("url" => $url, "title" => $title, "description" => $description, "color" => $color, "footer" => $footer, "author" => $author);
  $sys["embeds"] = array(0 => $embed);

  $curl = curl_init($webhook);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sys));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_exec($curl);
}

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DATABS);
if ($conn->connect_error) {
    die("Oop's, Somethings broken!");
}
