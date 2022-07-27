<?php

namespace SSD\Integrations\Spotify;

use SSD\Integrations\Spotify\Client as SpotifyClient;

final class Spotify
{
  private SpotifyClient $client;

  public function __construct()
  {
      $this->client = new SpotifyClient;
  }

  /**
   * @return object|null
   */
  public function getCurrentlyPlayingTrack(): ?object
  {
    return $this->client->getCurrentlyPlayingTrack();
  }
}