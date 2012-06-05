<? include "missAllodsTopLogic.php"; ?>
<table border="1">
    <tr>
        <td>#</td>
        <td><?= iconv("utf-8","windows-1251","Ник") ?></td>
        <td><?= iconv("utf-8","windows-1251","Голосов") ?></td>
        <td><?= iconv("utf-8","windows-1251","Ссылка") ?></td>
        <td><?= iconv("utf-8","windows-1251","График") ?></td>
    </tr>
    <? foreach ( $result as $miss ): ?>
    <tr <?= ($i < 11) ? 'style="background-color: #00FF00"' : '' ?>>
        <td><?= $i++?></td>
        <td><?= iconv("utf-8","windows-1251",$miss->nickname) ?></td>
        <td><?= $miss->votes ?></td>
        <td><a href="http://allods.mail.ru/media.php?item=<?= $miss->id ?>"><?= iconv("utf-8","windows-1251","Ссылка") ?></a></td>
        <td><a target="_blank" href="http://bsl-clan.ru/missAllods2012/missAllodsChart.php?missID=<?= $miss->id ?>"><?= iconv("utf-8","windows-1251","График") ?></a></td>
    </tr>
    <? endforeach; ?>
</table>