<?php
$cacheExpire = "1 day";
$cacheFile = "cache.dat";
$url = "https://tokyo.craigslist.org/search/apa?availabilityMode=0&format=rss&query=cowcowhomes%20LTD&sort=date";

$diff_from_file = time() - @filemtime($cacheFile);
$diff_from_current = is_string($cacheExpire) ? strtotime($cacheExpire) - time() : $cacheExpire;
if($diff_from_file <= $diff_from_current)
{
    $strJson = @file_get_contents($cacheFile);
} else {
    $ch = curl_init(); // 初期化
    curl_setopt( $ch, CURLOPT_URL, $url ); // URLの設定
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 出力内容を受け取る設定
    $result = curl_exec( $ch ); // データの取得
    curl_close($ch); // cURLのクローズ

    $strJson = xml_to_json($result);

    file_put_contents($cacheFile, $strJson);
}

echo $strJson;

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