<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vlogvinskiy
 * Date: 6/5/12
 * Time: 13:32 PM
 */
header( 'Content-Type: text/html; charset=UTF-8' );
include "missAllods2012Config.php";

if ( (int)$_GET[ 'missID' ] == 0 ) {
    exit;
}

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
SELECT *
FROM `missAllods2012`
WHERE `id`=:id
ORDER BY `date` ASC
";

$query = $pdo->prepare($sql);
$query->execute(array(
    'id' => (int)$_GET['missID']
));
$result = $query->fetchAll(PDO::FETCH_ASSOC);

?>
<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Time', 'Votes'],
                <? foreach($result as $item): ?>
                ["<?= date('Y-m-d H:i',$item['date']) ?>", <?= $item['votes'] ?>],
                <? endforeach; ?>
            ]);

            var options = {
                title:'<?= $result[0]['nickname'] ?>',
                hAxis:{title:'Date', titleTextStyle:{color:'red'}}
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div id="chart_div" style="width: 900px; height: 500px;"></div>
</body>
</html>