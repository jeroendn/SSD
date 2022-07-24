<?php

use SSD\Integrations\Steam\Client as SteamClient;
use SSD\Integrations\Steam\Entity\PlayerSummary;
use SSD\Integrations\Steam\Steam;

$steam = new Steam;
$steamClient = new SteamClient();
?>

<div id="widget-steam-top-10-played-games" class="refresh">
  <?php $playerSummary = $steamClient->getPlayerSummary(); ?>
  <img src="<?= $playerSummary->avatarMedium ?>">
  <p><?= $playerSummary->personaName ?></p>
  <p><?= PlayerSummary::PERSONA_STATES[$playerSummary->personaState] ?></p>
</div>