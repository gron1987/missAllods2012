<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vlogvinskiy
 * Date: 6/5/12
 * Time: 12:53 PM
 */
include "missAllods2012Config.php";

try {
    $pdo = new PDO(
        "mysql:dbname=" . $dbConfig[ 'dbname' ] . ";host=" . $dbConfig[ 'host' ],
        $dbConfig[ 'username' ],
        $dbConfig[ 'password' ]
    );
} catch ( PDOException $e ) {
    echo 'Connection failed: ' . $e->getMessage();
}

$result = null;

if(is_readable($cacheFile)){
    $file = file_get_contents($cacheFile);
    $result = unserialize($file);
    if(time() > $result['dateTo']){
        $result = null;
    }else{
        $result = $result['result'];
    }
}

if($result === NULL){
    $pdo->exec( "SET NAMES UTF8" );

    $sql = "
        SELECT ma1 . *
        FROM  `missAllods2012` ma1
        LEFT JOIN  `missAllods2012` ma2 ON ma1.id = ma2.id
        AND ma1.date < ma2.date
        WHERE ma2.id IS NULL
        ORDER BY  `ma1`.`votes` DESC
        LIMIT 0,50
    ";

    $query = $pdo->prepare( $sql );
    $query->execute();
    $result = $query->fetchAll( PDO::FETCH_OBJ );

    $cacheData = array(
        'result' => $result,
        'dateTo' => time() + 60*5
    );
    $fileHandler = fopen($cacheFile,'w+');
    fputs($fileHandler,serialize($cacheData));
    fclose($fileHandler);
}

$i = 1;
?>