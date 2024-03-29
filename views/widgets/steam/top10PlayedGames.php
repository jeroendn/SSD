<?php

use SSD\Integrations\Steam\Steam;

$platform = $platform ?? 'all';
$steam = new Steam;
?>

<div id="widget-steam-top-10-played-games<?= ($platform !== 'all' ? '-' . $platform : '') ?>" class="widget steam-widget">
  <div class="widget-heading">
    <p class="widget-title">Top 10 played games <?= ($platform !== 'all' ? '(' . ucfirst($platform) . ')' : '') ?></p>
  </div>
  <div class="widget-body">
    <?php $games = $steam->getTop10PlayedGames($platform); ?>
    <?php foreach ($games as $game): ?>
      <div class="game">
        <img src="https://media.steampowered.com/steamcommunity/public/images/apps/<?= $game->appId ?>/<?= $game->imgIconUrl ?>.jpg">
        <p><?= $game->name ?><span>&nbsp;<?= $game->getPlaytimeForever(true, $platform) ?> Hrs</span></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>