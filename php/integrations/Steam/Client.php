<?php

namespace SSD\Integrations\Steam;

use GuzzleHttp\Client as GuzzleClient;
use SSD\Integrations\Integration;
use Throwable;

final class Client extends Integration
{
  private string $steamId;
  private string $baseUrl = 'https://api.steampowered.com/';

  private string $urlGetOwnedGames = 'IPlayerService/GetOwnedGames/v0001/';

  public function __construct()
  {
    parent::__construct();

    $this->apiKey = API_KEY_STEAM;
    $this->steamId = API_STEAM_ID;
  }

  /**
   * @return array
   */
  public function getOwnedGames(): array
  {
    $url = $this->baseUrl . $this->urlGetOwnedGames . '?key=' . $this->apiKey . '&steamid=' . $this->steamId . '&include_appinfo=1&format=json';

    try {
      $response = (new GuzzleClient())->request('GET', $url);
    }
    catch (Throwable $e) {
      return []; // Silent fail
    }

    $games = json_decode($response->getBody(), true)['response']['games'] ?? [];

//    print_r($games);

    return $games;
  }
}