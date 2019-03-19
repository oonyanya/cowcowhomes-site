<?php
// ���g����
//
//  index.php?rss_url=[URL] �݂����ɂ��Ďg���܂��B[URL]��PHP��urlencode()�ȂǂŃG���R�[�h�����z��n���K�v������܂��B
//
//  ���ӓ_
//
//  ���O��Ԃ�:��_�ɒu���������܂�
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
//$supportsGzip = false;
if($format== "json")
	$reader = new RssReader();
else
	$reader = new CachedHttpReader();
$feed = $reader->get($url,$supportsGzip);

if($supportsGzip)
  echo header('Content-Encoding: gzip');

echo $feed->data;

?>