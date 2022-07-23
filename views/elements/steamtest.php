<?php

use SSD\Integrations\Steam\Steam;

$steam = new Steam();
?>

<section>
  <?php
  $games = $steam->getTop10PlayedGames();

  foreach ($games as $game) {
    echo $game['name'] . '<br>';
    echo '<img src="https://media.steampowered.com/steamcommunity/public/images/apps/'. $game['appid'] .'/'.$game['img_icon_url'].'.jpg">';
  }

  ?>
</section>