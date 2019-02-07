<?php
$cacheExpire = "6 hour";

if(isset($_GET["rss_url"]))
    $url = $_GET["rss_url"];
else
    exit("");

$cacheFile = sprintf("%s.dat",hasedStr($url));

$diff_from_file = time() - @filemtime($cacheFile);
$diff_from_current = is_string($cacheExpire) ? strtotime($cacheExpire) - time() : $cacheExpire;
if($diff_from_file <= $diff_from_current)
{
    $strJson = @file_get_contents($cacheFile);
} else {
    $result = get_xml($url);
    if(result == null)
        exit("");
    $strJson = xml_to_json($result);

    file_put_contents($cacheFile, $strJson);
}

echo $strJson;

function get_xml($url)
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

function hasedStr($s)
{
    return hash("sha256", $s, false);
}

//**********************************
// XML ⇒ JSONに変換する関数
//**********************************
function xml_to_json($xml)
{
    // コロンをアンダーバーに（名前空間対策）
    $xml = preg_replace("/<([^>]+?):([^>]+?)>/", "<$1_$2>", $xml);
    // プロトコルのは元に戻す
    $xml = preg_replace("/_\/\//", "://", $xml);
    // XML文字列をオブジェクトに変換（CDATAも対象とする）
    $objXml = simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
    // 属性を展開する
    xml_expand_attributes($objXml);
    // JSON形式の文字列に変換
    $json = json_encode($objXml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // "\/" ⇒ "/" に置換
    return preg_replace('/\\\\\//', '/', $json);
}

//**********************************
// XMLタグの属性を展開する関数
//**********************************
function xml_expand_attributes($node)
{
    if($node->count()) {
        foreach($node->children() as $child)
        {
            foreach($child->attributes() as $key => $val) {
                $node->addChild($child->getName()."@".$key, str_replace('&', '&amp;', $val));
            }
            xml_expand_attributes($child); // 再帰呼出
        }
    }
}

?>