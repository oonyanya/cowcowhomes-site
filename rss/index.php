<?php
// ■使い方
//
//  index.php?rss_url=[URL] みたいにして使います。[URL]はPHPのurlencode()などでエンコードした奴を渡す必要があります。
//
//  注意点
//
//  名前空間の:は_に置き換えられます
//
if(isset($_GET["rss_url"]))
  $url = $_GET["rss_url"];
else
  exit("");

$reader = new RssReader();
echo $reader->get($url);

class Cache
{
  protected $cache_expire;
  protected $cache_file;
  protected $cache_meta;

  public function __construct($url)
  {
    $this->cache_file = sprintf("%s.dat",$this->hasedStr($url));
    $this->cache_meta = sprintf("%s.meta",$this->hasedStr($url));
    if(file_exists($this->cache_meta))
      $this->cache_expire = @file_get_contents($this->cache_meta);
    else
      $this->cache_expire = "24 hour";
  }

  public function is_expire()
  {
    $diff_from_file = time() - @filemtime($this->cache_file);
    $diff_from_current = is_string($this->cache_expire) ? strtotime($this->cache_expire) - time() : $this->cache_expire;
    return $diff_from_file <= $diff_from_current;
  }

  public function set($content,$expire)
  {
    file_put_contents($this->cache_file, $content);
    if(!empty($expire))
      file_put_contents($this->cache_meta, $expire);
  }

  public function get()
  {
    return @file_get_contents($this->cache_file);
  }

  private function hasedStr($s)
  {
     return hash("sha256", $s, false);
  }
}

class RssReader
{
  public function get($url)
  {
    $cache = new Cache($url);

    if($cache->is_expire())
    {
      $strJson = $cache->get();
    } else {
      $result = $this->get_xml($url);
      if($result == null)
          return "";

      $xml = $this->parse_xmlstr($result);
      $strJson = $this->xml_to_json($xml);
      $update_span = $this->calc_expire_hour(
        intval($xml->channel->syn_updateFrequency),$xml->channel->syn_updatePeriod
      );

      $cache->set($strJson,$update_span);
    }

    return $strJson;
  }

  private function calc_expire_hour($freq,$period)
  {
    switch($period){
      case "hourly":
        $update_span = new DateInterval("PT1H"); //1時間未満だとサーバーが落ちるかもしれない
        $freq = 1;
        break;
      case "daily":
        $update_span = new DateInterval("P1D");
        break;
      case "weekly":
        $update_span = new DateInterval("P7D");
        break;
      case "monthly":
        $update_span = new DateInterval("P1M");
      case "yearly":
        $update_span = new DateInterval("P1Y");
    }
    $date = new DateTime("now");
    $expire_date = (new DateTime("now"))->add($update_span);
    $span_seconds = ($expire_date->getTimeStamp() - $date->getTimestamp()) / 3600 / $freq;
    return $date->add(new DateInterval("PT".$span_seconds."S"))->format(DateTime::RFC3339);
  }

  private function get_xml($url)
  {
     $ch = curl_init(); // 初期化
     curl_setopt( $ch, CURLOPT_URL, $url ); // URLの設定
     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 出力内容を受け取る設定
     $result = curl_exec( $ch ); // データの取得
     $errno = curl_errno($ch);
     curl_close($ch); // cURLのクローズ

     if($errno != CURLE_OK)
       return null;

     return $result;
  }

  //**********************************
  // XML ⇒ JSONに変換する関数
  //**********************************
  private function xml_to_json($xml)
  {
     // JSON形式の文字列に変換
     $json = json_encode($xml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
     // "\/" ⇒ "/" に置換
     return preg_replace('/\\\\\//', '/', $json);
  }

  private function parse_xmlstr($xml)
  {
    // コロンをアンダーバーに（名前空間対策）
    $xml = preg_replace("/<([^>]+?):([^>]+?)>/", "<$1_$2>", $xml);
    // プロトコルのは元に戻す
    $xml = preg_replace("/_\/\//", "://", $xml);
    // XML文字列をオブジェクトに変換（CDATAも対象とする）
    $objXml = simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
    // 属性を展開する
    $this->xml_expand_attributes($objXml);

    return $objXml;
  }

  //**********************************
  // XMLタグの属性を展開する関数
  //**********************************
  private function xml_expand_attributes($node)
  {
    if($node->count()) {
      foreach($node->children() as $child)
      {
        foreach($child->attributes() as $key => $val) {
          $node->addChild($child->getName()."@".$key, str_replace('&', '&amp;', $val));
        }
        $this->xml_expand_attributes($child); // 再帰呼出
      }
    }
  }
}


?>