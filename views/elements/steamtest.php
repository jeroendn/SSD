<?php

use SSD\Integrations\Steam\Steam;

$steam = new Steam();
?>

<section>
  <?php
  $games = $steam->getTop10PlayedGames();

  foreach ($games as $game) {
//    var_dump($game);
    echo '<img src="https://media.steampowered.com/steamcommunity/public/images/apps/'. $game->appId .'/'.$game->imgIconUrl.'.jpg"> ';
    echo $game->name . ' - ';
    echo $game->playtimeForever(true) . ' Hrs<br>';
  }

  ?>
</section>