<?php

namespace SSD\Integrations\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

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

//    $this->printAuthUrl();

//    $this->initNewTokens();

    $tokens = $this->getTokens();
    $accessToken = $tokens['accessToken'] ?? null;
    $refreshToken = $tokens['refreshToken'] ?? null;

    if ($accessToken) {
      $this->session->setAccessToken($accessToken);
      $this->session->setRefreshToken($refreshToken);
    }
    else {
      $this->session->requestAccessToken($refreshToken);
      $this->setTokens($this->session->getAccessToken(), $this->session->getRefreshToken());
    }

    $this->api->setAccessToken($this->session->getAccessToken());

//    print_r($this->api->getTrack('4uLU6hMCjMI75M1A2tKUQC'));
//      print_r($this->api->getMyCurrentPlaybackInfo());
//      print_r($this->api->me());
  }

  /**
   * FOR DEVELOPMENT ONLY
   * @return void
   */
  private function printAuthUrl(): void
  {
    print_r($this->session->getAuthorizeUrl(['scope' => ['user-read-playback-state', 'user-read-currently-playing']]));
  }

  /**
   * FOR DEVELOPMENT ONLY
   * @return void
   */
  private function initNewTokens(): void
  {
    $this->session->requestAccessToken('AQAYSq5qI8_KqSCJ_yNi8zpoD0xY-lN3hxvpR19hyDdl3Dn4R6sGc6KWC1hgOHZLB44H3ldJDTAk4NQ0yCreYdUvvC-6rwmfRL6EihO3baV43OXN-9DCTT8xMfW7JISqmTc_9u9fm1m4wvhx9iT7hbIf7sEC9HTkRgHH93vXHk03b2L7EH3IWHv7Sx-GqBoaIAZ2sTWxsHsmC4DlxGzyqBC-mbwYbAjl6vmt3qiR4z2HPchfi3s'); // Code returned from Spotify in the redirected url parameters
    echo $this->session->getAccessToken() . '&nbsp;<br>&nbsp;';
    echo $this->session->getRefreshToken();
    $this->setTokens($this->session->getAccessToken(), $this->session->getRefreshToken());
    die;
  }

  /**
   * Get the current access and refresh tokens from a json file.
   * @return mixed
   */
  private function getTokens(): mixed
  {
    return json_decode(file_get_contents(__DIR__ . '/../../../spotify-tokens.json'), true);
  }

  /**
   * Set the current access and refresh tokens to a json file.
   * @param string $accessToken
   * @param string $refreshToken
   * @return bool Success status
   */
  private function setTokens(string $accessToken, string $refreshToken): bool
  {
    $json = array('accessToken' => $accessToken, 'refreshToken' => $refreshToken);

    return (bool)file_put_contents(__DIR__ . '/../../../spotify-tokens.json', json_encode($json));
  }

  /**
   * @return object
   */
  public function getCurrentlyPlayingTrack(): object
  {
    $currentlyPlayingTrackData = $this->api->getMyCurrentTrack();

//    print_r((array)$currentlyPlayingTrackData);

    return $currentlyPlayingTrackData;
  }
}