<?
header('Content-Type: text/html; charset=UTF-8');
include "missAllodsTopLogic.php";
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
                <td>График</td>
            </tr>
            <? foreach ( $result as $miss ): ?>
            <tr <?= ($i < 11) ? 'style="background-color: #00FF00"' : '' ?>>
                <td><?= $i++?></td>
                <td><?= $miss->nickname ?></td>
                <td><?= $miss->votes ?></td>
                <td><a href="http://allods.mail.ru/media.php?item=<?= $miss->id ?>">Ссылка</a></td>
                <td><a target="_blank" href="http://bsl-clan.ru/missAllods2012/missAllodsChart.php?missID=<?= $miss->id ?>">График</a></td>
            </tr>
            <? endforeach; ?>
        </table>
    </body>
</html>