<?php

use SSD\Integrations\Steam\Steam;

$steam = new Steam;
?>

<div id="widget-steam-top-10-played-games" class="refresh">
  <?php
  $games = $steam->getTop10PlayedGames();

  foreach ($games as $game) {
    echo '<img src="https://media.steampowered.com/steamcommunity/public/images/apps/' . $game->appId . '/' . $game->imgIconUrl . '.jpg"> ';
    echo $game->name . ' - ';
    echo $game->getPlaytimeForever(true) . ' Hrs<br>';
  }
  ?>
</div>