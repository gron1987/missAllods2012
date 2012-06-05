<? include "missAllodsTopLogic.php"; ?>
<div style="width: 100%;background: url(/img/menu_bg22.gif) #85886E;padding:5px; margin-left:-5px; font-family:Garamond; font-size: 16px; font-weight:bold; z-index:2;padding-left:10px;">
    <?= iconv( "utf-8", "windows-1251", "Мисс Аллоды 2012" ) ?>
</div>
<table border="1" style="margin-left: -5px; margin-right: 5px;">
    <tr>
        <td>#</td>
        <td><?= iconv( "utf-8", "windows-1251", "Ник" ) ?></td>
        <td><?= iconv( "utf-8", "windows-1251", "Голосов" ) ?></td>
    </tr>
    <? foreach ( $result as $miss ): ?>
    <? if ( $i > 10 ) break; ?>
    <tr style="background-color: #00cc00;">
        <td><?= $i++?></td>
        <td><?= substr(iconv( "utf-8", "windows-1251", $miss->nickname ),0,30) ?></td>
        <td><?= $miss->votes ?></td>
    </tr>
    <? endforeach; ?>
</table>
<a href="missAllods">
    <?= iconv( "utf-8", "windows-1251", "Больше информации" ) ?>
</a>