<?php

namespace SSD\Integrations\Steam;

use GuzzleHttp\Client as GuzzleClient;
use SSD\Integrations\Steam\Entity\OwnedGame;
use SSD\Integrations\Steam\Entity\PlayerSummary;
use Throwable;

final class Client
{
  private string $apiKey;
  private string $steamId;
  private string $baseUrl = 'https://api.steampowered.com/';

  private string $urlGetOwnedGames = 'IPlayerService/GetOwnedGames/v1/';
  private string $urlGetPlayerSummaries = 'ISteamUser/GetPlayerSummaries/v2/';

  public function __construct()
  {
    $this->apiKey = API_KEY_STEAM;
    $this->steamId = API_STEAM_ID;
  }

  /**
   * @return PlayerSummary|null Null on failure of request.
   */
  public function getPlayerSummary(): ?PlayerSummary
  {
    $url = $this->baseUrl . $this->urlGetPlayerSummaries . '?key=' . $this->apiKey . '&steamids=' . $this->steamId . '&format=json';

    try {
      $response = (new GuzzleClient())->request('GET', $url);
    }
    catch (Throwable $e) {
      return null; // Silent fail
    }

    $playerSummaryData = json_decode($response->getBody(), true)['response']['players']['0'] ?? [];

    return new PlayerSummary($playerSummaryData);
  }

  /**
   * @return OwnedGame[]
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

    $gamesData = json_decode($response->getBody(), true)['response']['games'] ?? [];

    $games = [];
    foreach ($gamesData as $gameData) {
      $games[] = new OwnedGame($gameData);
    }

    return $games;
  }
}