<?php

use SSD\Integrations\Steam\Client as SteamClient;
use SSD\Integrations\Steam\Entity\PlayerSummary;
use SSD\Integrations\Steam\Steam;

$steam = new Steam;
$steamClient = new SteamClient();

$playerSummary = $steamClient->getPlayerSummary();
?>

<div id="widget-steam-profile-status" class="widget steam-widget auto-refresh" refresh-rate="10000">
  <p class="widget-title">Steam profile status</p>
  <div style="display: flex;">
    <img src="<?= $playerSummary->avatarMedium ?>">
    <div style="display: block; margin-left: 10px;">
      <p><?= $playerSummary->personaName ?></p>
      <p><?= PlayerSummary::PERSONA_STATES[$playerSummary->personaState] ?></p>
    </div>
  </div>
  <p>Currently playing: <?= $playerSummary->gameExtraInfo ?? '-' ?></p>
</div>