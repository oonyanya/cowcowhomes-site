<?php
// ■使い方
//
//  index.php?rss_url=[URL] みたいにして使います。[URL]はPHPのurlencode()などでエンコードした奴を渡す必要があります。
//
//  注意点
//
//  名前空間の:は_に置き換えられます
//
include 'rss_reader.php';

if(isset($_GET["rss_url"]))
  $url = $_GET["rss_url"];
else
  exit("");

if(isset($_GET["format"]))
  $format = $_GET["format"];
else
  $format = "json";

$supportsGzip = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
$supportsGzip = false;
if($format== "json")
	$reader = new RssReader();
else
	$reader = new CachedHttpReader();
$feed = $reader->get($url,$supportsGzip);

if($supportsGzip)
  echo header('Content-Encoding: gzip');

echo $feed->data;

?>