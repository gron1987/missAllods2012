<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vlogvinskiy
 * Date: 6/5/12
 * Time: 10:10 AM
 * To change this template use File | Settings | File Templates.
 */

$dbConfig = array( 'host' => 'localhost', 'username' => 'root', 'password' => '', 'dbname' => 'test' );

try {
    $pdo = new PDO(
        "mysql:dbname=" . $dbConfig[ 'dbname' ] . ";host=" . $dbConfig[ 'host' ],
        $dbConfig[ 'username' ],
        $dbConfig[ 'password' ]
    );
} catch ( PDOException $e ) {
    echo 'Connection failed: ' . $e->getMessage();
}

$pdo->exec( "SET NAMES UTF8" );

$sql = "INSERT INTO `missAllods2012`(`id`,`nickname`,`votes`,`date`) VALUE(:id,:nickname,:votes,:date)";

// get page count
$curlMainPage = curl_init();
curl_setopt_array( $curlMainPage, array(
        CURLOPT_URL => 'http://allods.mail.ru/media.php?section=miss_ao2012',
        CURLOPT_RETURNTRANSFER => true
    )
);
echo "Start load main page " . PHP_EOL;
$mainPage = curl_exec( $curlMainPage );
curl_close( $curlMainPage );

$pageLinks = array();
preg_match_all( "/media\.php\?section=miss_ao2012&page=([0-9]*)/i", $mainPage, $pageLinks );
$pageLinks = array_unique( $pageLinks[ 1 ] );
array_unshift( $pageLinks, "1" );
echo "Get " . sizeof( $pageLinks ) . " pages to view " . PHP_EOL;

// get all misses (item ID)
$infoLinks = array();
foreach ( $pageLinks as $page ) {
    $curlPage = curl_init();
    curl_setopt_array( $curlPage, array(
            CURLOPT_URL => 'http://allods.mail.ru/media.php?section=miss_ao2012&page=' . $page,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'BSL CLAN SITE BOT'
        )
    );
    echo "Get " . $page . ' page ' . PHP_EOL;
    $pageData = curl_exec( $curlPage );

    curl_close( $curlPage );

    $pageInfoLinks = array();
    preg_match_all( "/media\.php\?item=([0-9]*)/i", $pageData, $pageInfoLinks );

    // remove 18 last (right gallery block 2 tab, 9 photo in each)
    for ( $i = 1; $i <= 18; $i++ ) {
        array_pop( $pageInfoLinks[ 1 ] );
    }
    $pageInfoLinks = array_unique( $pageInfoLinks[ 1 ] );

    $infoLinks = array_merge( $infoLinks, $pageInfoLinks );
    echo "Get " . sizeof( $pageInfoLinks ) . " user links " . PHP_EOL;
}

// view all miss photos and get nickname + count
$infoData = array();
foreach ( $infoLinks as $itemID ) {
    echo "Get user " . $itemID . " page " . PHP_EOL;
    $curlItemPage = curl_init();
    curl_setopt_array( $curlItemPage, array(
            CURLOPT_URL => 'http://allods.mail.ru/media.php?item=' . $itemID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'BSL CLAN SITE BOT'
        )
    );

    $itemInfo = curl_exec( $curlItemPage );

    curl_close( $curlItemPage );

    $username = array();
    preg_match_all( "/<td class=\"tcat\" valign=\"top\"><b>(.*)<\/b><\/td>/i", $itemInfo, $username );
    $username = mb_convert_encoding( $username[ 1 ][ 0 ], 'UTF-8', 'HTML-ENTITIES' );

    $votes = array();
    $strToFindCp1251 = iconv( "utf-8", "windows-1251", "Голосов за эту работу:" );
    preg_match_all( "/$strToFindCp1251 ([0-9]*)/i", $itemInfo, $votes );
    $votes = $votes[ 1 ][ 0 ];

    $query = $pdo->prepare( $sql );
    echo "Insert data for userID " . $itemID . PHP_EOL;
    $query->execute(
        array(
            'id' => $itemID,
            'nickname' => $username,
            'votes' => $votes,
            'date' => time(),
        )
    );
}

?>