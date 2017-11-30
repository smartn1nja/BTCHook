<?php
// SNOOP URL https://api.coindesk.com/v1/bpi/currentprice/USD.json

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

function webhook($url, $linkUrl, $linkTitle, $linkDesc, $embedColor, $botName, $botAvatar, $content) {
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
  $e = array("url" => $linkUrl, "title" => $linkTitle, "description" => $linkDesc, "color" => $color);
  $sys["embeds"] = array(0 => $e);
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sys));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_exec($curl);
}


$lookup = getCurlData("https://api.coindesk.com/v1/bpi/currentprice/USD.json");
$obj = json_decode($lookup);
$rate = $obj->bpi->USD->rate;
$rate = str_replace(',', '', $rate);
$rate = round($rate, 2);
$update = $obj->time->updateduk;
$url = "https://discordapp.com/api/webhooks/385546436005330945/sWnie_Gdg7nlGodw6nBhtj0SgqjEWhMVUiP1_Q0u9MFJD-JEP6Kj4Po2BRo-zE03ZQQE";
$linkUrl = "http://preev.com/btc/usd";
$linkTitle = "Current: $". $rate;
$linkDesc = "Last updated ".$update;
if($rate > 10000 ){
$embedColor = "#64dd17";
} else {
	$embedColor = "#c62828";
}
$botName = "MEGALUL";
$botAvatar = "https://i.rexsdev.com/xVQzt-DdwRM-yic17.png";
$content = null;
webhook($url, $linkUrl, $linkTitle, $linkDesc, $embedColor, $botName, $botAvatar, $content);
