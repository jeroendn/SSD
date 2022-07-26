<?php

use SSD\Integrations\Steam\Client as SteamClient;
use SSD\Integrations\Steam\Entity\PlayerSummary;
use SSD\Integrations\Steam\Steam;

$steam = new Steam;
$steamClient = new SteamClient();

$playerSummary = $steamClient->getPlayerSummary();
?>

<div id="widget-steam-profile-status" class="widget steam-widget auto-refresh" refresh-rate="10000">
  <div class="widget-heading">
    <p class="widget-title">Steam profile status</p>
  </div>
  <div class="widget-body">
    <div style="display: flex; padding: 3px;">
      <img src="<?= $playerSummary->avatarMedium ?>" style="height: max-content;">
      <div style="display: block; margin-left: 10px;">
        <p><?= $playerSummary->personaName ?></p>
        <p><?= PlayerSummary::PERSONA_STATES[$playerSummary->personaState] ?></p>
        <?php if (isset($playerSummary->gameExtraInfo)): ?><p>Playing: <?= $playerSummary->gameExtraInfo ?></p><?php endif; ?>
      </div>
    </div>
  </div>
</div>