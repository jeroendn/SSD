<?php

namespace SSD\Integrations\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIAuthException;

final class Client
{
  private Session $session;
  private SpotifyWebAPI $api;
  private array $tokens;

  public function __construct()
  {
    $this->session = new Session(
      API_SPOTIFY_CLIENT_ID,
      API_SPOTIFY_CLIENT_SECRET,
      'https://dramuga.jeroendn.nl' // Must match redirectUri in Spotify application dashboard
    );
    $this->api = new SpotifyWebAPI;

    $this->login();
  }

  /**
   * @return void
   */
  private function login(): void
  {
//    $this->printAuthUrl();

//    $this->initNewTokens();

    if (empty($tokens)) {
      $this->tokens = $this->getTokens(); // TODO Find a way were we don't request the tokens at every ajax request
    }

    $accessToken = $this->tokens['accessToken'] ?? null;
    $refreshToken = $this->tokens['refreshToken'] ?? null;
    $tokenExpiration = $this->tokens['tokenExpiration'] ?? null;

    if ($accessToken && $tokenExpiration > time()) { // If we have a token, and it's not expired, use it
      $this->session->setAccessToken($accessToken);
      $this->session->setRefreshToken($refreshToken);
    }
    else {
      $this->session->refreshAccessToken($refreshToken);
      if (!$this->setTokens($this->session->getAccessToken(), $this->session->getRefreshToken(), $this->session->getTokenExpiration())) { // Try one more time on failure
        sleep(1); // Wait a second, before try again
        $this->setTokens($this->session->getAccessToken(), $this->session->getRefreshToken(), $this->session->getTokenExpiration());
      }

      $this->tokens = $this->getTokens(); // Update tokens
    }

    $this->api->setAccessToken($this->session->getAccessToken());
  }

  /**
   * FOR DEVELOPMENT ONLY
   * @return void
   */
  private function printAuthUrl(): void
  {
    print_r($this->session->getAuthorizeUrl(['scope' => ['user-read-playback-state', 'user-read-currently-playing']]));
    die;
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
    return json_decode(file_get_contents(__DIR__ . '/../../../config/spotify-tokens.json'), true);
  }

  /**
   * Set the current access and refresh tokens to a json file.
   * @param string $accessToken
   * @param string $refreshToken
   * @param int $tokenExpiration
   * @return bool Success status
   */
  private function setTokens(string $accessToken, string $refreshToken, int $tokenExpiration): bool
  {
    $json = array('accessToken' => $accessToken, 'refreshToken' => $refreshToken, 'tokenExpiration' => $tokenExpiration);

    return (bool)file_put_contents(__DIR__ . '/../../../config/spotify-tokens.json', json_encode($json));
  }

  /**
   * @return object|null
   */
  public function getCurrentlyPlayingTrack(): ?object
  {
    try {
      $getCurrentlyPlayingTrack = $this->api->getMyCurrentTrack();
    }
    catch (SpotifyWebAPIAuthException $e) {
      $this->login();

      try {
        $getCurrentlyPlayingTrack = $this->api->getMyCurrentTrack();
      }
      catch (SpotifyWebAPIAuthException $e) {
        return null;
      }
    }

    return $getCurrentlyPlayingTrack;
  }
}