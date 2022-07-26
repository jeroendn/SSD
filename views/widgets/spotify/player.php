<?php

use SSD\Integrations\Spotify\Client as SpotifyClient;

$spotifyClient = $_SESSION['spotify_client'] ?? new SpotifyClient; // Do not create a new instance if we already have one

$playbackState = $spotifyClient->getCurrentlyPlayingTrack();
?>

<div id="widget-spotify-player" class="widget spotify-widget auto-refresh" refresh-rate="5000" style="background-image: url(<?= $playbackState->item->album->images[0]->url ?>)">
  <div class="widget-heading">
    <p class="widget-title">Now playing</p>
  </div>
  <div class="widget-body">
    <div class="banner-and-names">
      <img src="<?= $playbackState->item->album->images[0]->url ?>">
      <div class="names">
        <p><?= $playbackState->item->name ?></p>
        <p><?= $playbackState->item->album->name ?></p>
        <p><?php
          $isFirstLoop = true;
          foreach ($playbackState->item->artists as $artist) {
            if ($isFirstLoop) {
              echo $artist->name;
              $isFirstLoop = false;
            }
            else {
              echo ', ' . $artist->name;
            }
          }
          ?>
        </p>
      </div>
    </div>
    <?= $playbackState->progress_ms ?>
    <progress id="spotify-player-progress-bar" max="<?= $playbackState->item->duration_ms ?>" value="<?= $playbackState->progress_ms ?>"> 70%</progress>
    <?= $playbackState->item->duration_ms ?>
  </div>
</div>