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
        $publicApiData = file_get_contents('https://api.steampowered.com/ISteamChartsService/GetMostPlayedGames/v1/');
        $publicApiData = json_decode($publicApiData);

        $ownedGames = $steamClient->getOwnedGames();

        $maxItems = 10;
        ?>
        <?php for ($i = 0; $i < 10; $i++): ?>
            <?php
            $app               = $publicApiData?->response?->ranks[$i];
            $appDetailsPrivate = $ownedGames[$app->appid] ?? null;
            if ($appDetailsPrivate) {
                $appName   = $appDetailsPrivate->name;
                $appImgUrl = 'https://media.steampowered.com/steamcommunity/public/images/apps/' . $appDetailsPrivate->appId . '/' . $appDetailsPrivate->imgIconUrl . '.jpg';
            }
            else {
                $appDetailsPublic = $steamClient->getAppDetails($app->appid);

                $appName = $appDetailsPublic->name;

                $appImgUrl = 'https://cdn.cloudflare.steamstatic.com/steam/apps/' . $app->appid . '/capsule_184x69.jpg';

                if (@file_get_contents($appImgUrl) === false) {
                    $appImgUrl = 'https://cdn.cloudflare.steamstatic.com/steam/apps/' . $app->appid . '/header.jpg';
                }
            }
            ?>
            <div class="game">
                <img src="<?= $appImgUrl ?>">
                <p><?= $appName ?><span>&nbsp;<?= number_format($app->peak_in_game) ?></span></p>
            </div>
        <?php endfor; ?>
    </div>
</div>