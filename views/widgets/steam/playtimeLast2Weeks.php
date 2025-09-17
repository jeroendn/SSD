<?php
/**
 * @var OwnedGame $game
 */

use SSD\Integrations\Steam\Entity\OwnedGame;
use SSD\Integrations\Steam\Steam;

$steam = new Steam;
?>

<?php
$games         = $steam->getTop10PlayedGamesLast2Weeks();
$totalPlaytime = 0;
foreach ($games as $game) {
    $totalPlaytime += $game->playtime2Weeks;
}
?>
<div id="widget-steam-playtime-last-2-weeks" class="widget steam-widget" refresh-rate="120000">
    <div class="widget-heading">
        <p class="widget-title">Playtime last 2 weeks<span style="float: right;"><?= number_format($totalPlaytime / 60, 1) ?> Hrs</span></p>
    </div>
    <div class="widget-body">
        <?php foreach ($games as $game): ?>
            <div class="game">
                <img src="https://media.steampowered.com/steamcommunity/public/images/apps/<?= $game->appId ?>/<?= $game->imgIconUrl ?>.jpg">
                <p><?= $game->name ?><span>&nbsp;<?= $game->getPlaytime2Weeks(true, 1) ?> Hrs</span></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>