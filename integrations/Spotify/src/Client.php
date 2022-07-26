<?php

namespace SSD\Integrations\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SSD\Integrations\Steam\Entity\PlayerSummary;

final class Client
{
  private Session $session;
  private SpotifyWebAPI $api;

  public function __construct()
  {
    $this->session = new Session(
      API_SPOTIFY_CLIENT_ID,
      API_SPOTIFY_CLIENT_SECRET,
      'https://dramuga.jeroendn.nl' // Must match redirectUri in Spotify application dashboard
    );
    $this->api = new SpotifyWebAPI;

//    print_r($this->session->getAuthorizeUrl(['scope' => ['user-read-playback-state', 'user-read-currently-playing']]));

//      $this->session->requestAccessToken(API_SPOTIFY_AUTH_CODE);
//    dd($this->session->getAccessToken());

    $this->api->setAccessToken(API_SPOTIFY_ACCESS_TOKEN);

//    print_r($this->api->getTrack('4uLU6hMCjMI75M1A2tKUQC'));
//      print_r($this->api->getMyCurrentPlaybackInfo());
//      print_r($this->api->me());
  }

  /**
   * @return object
   */
  public function getCurrentlyPlayingTrack()
  {
    $currentlyPlayingTrackData = $this->api->getMyCurrentTrack();

//    print_r((array)$currentlyPlayingTrackData);

    return $currentlyPlayingTrackData;
  }
}