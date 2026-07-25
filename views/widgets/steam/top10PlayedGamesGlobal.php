<?php

use SSD\Integrations\Steam\Client;

$steamClient = new Client;
?>

<div id="widget-steam-top-10-played-games-global" class="widget steam-widget" refresh-rate="120000">
    <div class="widget-heading">
        <p class="widget-title">Most played global</p>
    </div>
    <div class="widget-body">
        <?php
        $mostPlayed = $steamClient->getMostPlayedGames();
        $apps       = $steamClient->getApps(array_keys($mostPlayed));
        ?>
        <?php foreach ($mostPlayed as $appId => $playerCount): ?>
            <?php
            $app       = $apps[$appId] ?? null;
            $appImgUrl = $app?->getIconUrl() ?? 'https://shared.cloudflare.steamstatic.com/store_item_assets/steam/apps/' . $appId . '/capsule_184x69.jpg';
            ?>
            <div class="game">
                <img src="<?= $appImgUrl ?>">
                <p><?= htmlspecialchars($app->name ?? 'App ' . $appId) ?><span>&nbsp;<?= number_format($playerCount) ?></span></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
