<?php

use SSD\Integrations\Spotify\Helper as SpotifyHelper;
use SSD\Integrations\Spotify\Spotify;

$spotify = new Spotify; // Do not create a new instance if we already have one

$playbackState = $spotify->getCurrentlyPlayingTrack();
?>

<?php if ($playbackState !== null): ?>

  <div id="widget-spotify-player" class="widget spotify-widget auto-refresh" refresh-rate="5000" style="background-image: url(<?= $playbackState->item->album->images[0]->url ?>)">
    <div class="widget-heading">
      <p class="widget-title">Now playing</p>
    </div>
    <div class="widget-body">
      <div class="banner-and-names">
        <img src="<?= $playbackState->item->album->images[1]->url ?>">
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
      <div class="progress-bar">
        <div class="durations">
          <span class="current-duration" duration="<?= $playbackState->progress_ms ?>"><?= SpotifyHelper::msToReadableString($playbackState->progress_ms) ?></span>
          <span class="total-duration"><?= SpotifyHelper::msToReadableString($playbackState->item->duration_ms) ?></span>
        </div>
        <progress id="spotify-player-progress-bar" max="<?= $playbackState->item->duration_ms ?>" value="<?= $playbackState->progress_ms ?>"></progress>
      </div>
    </div>
  </div>

<?php else: ?>

  <div id="widget-spotify-player" class="widget spotify-widget auto-refresh" refresh-rate="5000">
    <div class="widget-heading">
      <p class="widget-title">Now playing</p>
    </div>
    <div class="widget-body">
      <p>No recent activity</p>
    </div>
  </div>

<?php endif; ?>