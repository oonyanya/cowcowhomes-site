<?php

class CacheMeta
{
  public $expire;
  public $etag;
  public function __construct($expire,$etag)
  {
    $this->expire = $expire;
    $this->etag = $etag;
  }
}

class Cache
{
  protected $cache_file;
  protected $compressed_cache_file;
  protected $cache_meta;
  protected $allow_compress;

  public function __construct($url,$allow_compress)
  {
    $this->allow_compress = $allow_compress;
    $this->cache_file = sprintf("%s.dat",$this->hasedStr($url));
    $this->compressed_cache_file = sprintf("%s.gz",$this->hasedStr($url));
    $this->cache_meta = sprintf("%s.meta",$this->hasedStr($url));
  }

  public function is_not_expire()
  {
    $expire_str = $this->get_expire();
    if($expire_str == "")
      return false;
    $current_time = new DateTime("now");
    $expire = new DateTime();
    return $current_time <= $expire;
  }

  public function set($content,$expire)
  {
    file_put_contents($this->cache_file, $content);
    file_put_contents($this->compressed_cache_file, gzencode($content));
    if(!empty($expire))
    {
      $etag = $this->hasedStr($content);
      $meta = new CacheMeta($expire,$etag);
      file_put_contents($this->cache_meta, json_encode($meta));
    }
  }

  public function get()
  {
    if($this->allow_compress)
      return @file_get_contents($this->compressed_cache_file);
    else
      return @file_get_contents($this->cache_file);
  }

  public function get_etag()
  {
    if(!file_exists($this->cache_meta))
      return "";
    $meta = json_decode(@file_get_contents($this->cache_meta));
    return $meta->etag;
  }

  public function get_expire()
  {
    if(!file_exists($this->cache_meta))
      return "";
    $meta = json_decode(@file_get_contents($this->cache_meta));
    return $meta->expire;
  }

  private function hasedStr($s)
  {
     return hash("sha256", $s, false);
  }
}

class Feed
{
  public $data;
  public $etag;
  public function __construct($d,$e)
  {
    $this->data = $d;
    $this->etag = $e;
  }
}

class CachedHttpReader
{
  public function getif($url,$get_compress,$non_match)
  {
    $cache = new Cache($url,$get_compress);
    if($cache->get_etag() == $non_match)
      return new Feed(null,$cache->get_etag());
    else
      return $this->get($url,$get_compress);
  }

  public function get($url,$get_compress)
  {
    $cache = new Cache($url,$get_compress);

    if($cache->is_not_expire())
    {
      $strJson = $cache->get();
    } else {
      $result = $this->get_http_response($url);
      if($result == null)
          return "";

      $freq = 1;
      $period = "daily";
      $parsed_str = $this->parse($result,$freq,$period);
      $update_span = $this->calc_expire_hour($freq,$period);

      $cache->set($parsed_str,$update_span);
      $parsed_str = $cache->get(); //キャッシュに格納した奴を返さないといけない
    }

    return new Feed($parsed_str,$cache->get_etag());
  }

  protected function parse($str,&$freq,&$period)
  {
    return $str;
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
        break;
      case "yearly":
        $update_span = new DateInterval("P1Y");
        break;
      default:
        $update_span = new DateInterval("P1D");
        $freq = 1;
    }
    $date = new DateTime("now");
    $expire_date = (new DateTime("now"))->add($update_span);
    $span_seconds = ($expire_date->getTimeStamp() - $date->getTimestamp()) / $freq;
    return $date->add(new DateInterval("PT".$span_seconds."S"))->format(DateTime::RFC822);
  }

  private function get_http_response($url)
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
}

class CachedXmlReader extends CachedHttpReader
{
  protected function parse($str,&$freq,&$period)
  {
    $xml = $this->parse_xmlstr($str);
    return $xml;
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

class RssReader extends CachedXmlReader
{
  protected function parse($str,&$freq,&$period)
  {
    $xml = parent::parse($str,$freq,$period);
    $strJson = $this->xml_to_json($xml);
    $freq = intval($xml->channel->syn_updateFrequency);
    $period = $xml->channel->syn_updatePeriod;
    return $strJson;
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

}

?>
