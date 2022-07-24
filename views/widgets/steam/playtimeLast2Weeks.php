<?php

use SSD\Integrations\Steam\Steam;

$steam = new Steam;
?>

<div id="widget-steam-top-10-played-games" class="widget steam-widget auto-refresh">
  <p class="widget-title">Playtime last 2 weeks</p>
  <?php $games = $steam->getTop10PlayedGamesLast2Weeks(); ?>
  <?php foreach ($games as $game): ?>
    <div class="game">
      <img src="https://media.steampowered.com/steamcommunity/public/images/apps/<?= $game->appId ?>/<?= $game->imgIconUrl ?>.jpg">
      <p><?= $game->name ?><span>&nbsp;<?= $game->getPlaytime2Weeks(true) ?> Hrs</span></p>
    </div>
  <?php endforeach; ?>
</div>