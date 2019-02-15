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

  public function __construct($url)
  {
    $this->cache_file = sprintf("%s.dat",$this->hasedStr($url));
    $this->cache_expire = "6 hour";
  }

  public function is_expire()
  {
    $diff_from_file = time() - @filemtime($this->cache_file);
    $diff_from_current = is_string($this->cache_expire) ? strtotime($this->cache_expire) - time() : $this->cache_expire;
    return $diff_from_file <= $diff_from_current;
  }

  public function set($content)
  {
    file_put_contents($this->cache_file, $content);
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
          exit("");
      $strJson = $this->xml_to_json($result);

      $cache->set($strJson);
    }

    return $strJson;
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
     // コロンをアンダーバーに（名前空間対策）
     $xml = preg_replace("/<([^>]+?):([^>]+?)>/", "<$1_$2>", $xml);
     // プロトコルのは元に戻す
     $xml = preg_replace("/_\/\//", "://", $xml);
     // XML文字列をオブジェクトに変換（CDATAも対象とする）
     $objXml = simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
     // 属性を展開する
     $this->xml_expand_attributes($objXml);
     // JSON形式の文字列に変換
     $json = json_encode($objXml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
     // "\/" ⇒ "/" に置換
     return preg_replace('/\\\\\//', '/', $json);
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