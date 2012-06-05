<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vlogvinskiy
 * Date: 6/5/12
 * Time: 12:53 PM
 */
header('Content-Type: text/html; charset=UTF-8');
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
$result = $query->fetchAll( PDO::FETCH_ASSOC );

$i = 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Miss Allods 2012</title>
        <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <table border="1">
            <tr>
                <td>#</td>
                <td>Ник</td>
                <td>Голосов</td>
                <td>Ссылка</td>
            </tr>
            <? foreach ( $result as $miss ): ?>
            <tr <?= ($i < 11) ? 'style="background-color: #00FF00"' : '' ?>>
                <td><?= $i++?></td>
                <td><?= $miss[ 'nickname' ] ?></td>
                <td><?= $miss[ 'votes' ] ?></td>
                <td><a href="http://allods.mail.ru/media.php?item=<?= $miss[ 'id' ] ?>">Ссылка</a></td>
            </tr>
            <? endforeach; ?>
        </table>
    </body>
</html>