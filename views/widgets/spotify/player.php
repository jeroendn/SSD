<?php

use SSD\Integrations\Spotify\Client as SpotifyClient;

$spotifyClient = new SpotifyClient();

$playbackState = $spotifyClient->getCurrentlyPlayingTrack();
?>

<div id="widget-spotify-player" class="widget spotify-widget auto-refresh" refresh-rate="5000">
  <p class="widget-title">Now playing</p>
  <?= $playbackState->item->name ?><br>
  <?= $playbackState->item->album->name ?><br>
  <?= $playbackState->item->artists[0]->name ?><br>
  <?= $playbackState->progress_ms ?>
  <progress id="spotify-player-progress-bar" max="<?= $playbackState->item->duration_ms ?>" value="<?= $playbackState->progress_ms ?>"> 70% </progress>
  <?= $playbackState->item->duration_ms ?>
  <br>
  <img src="<?= $playbackState->item->album->images[2]->url ?>">
</div>