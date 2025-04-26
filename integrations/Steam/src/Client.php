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
    private const string        BASE_URL                 = 'https://api.steampowered.com/';
    private const string        URL_GET_OWNED_GAMES      = 'IPlayerService/GetOwnedGames/v1/';
    private const string        URL_GET_PLAYER_SUMMARIES = 'ISteamUser/GetPlayerSummaries/v2/';

    private const string BASE_URL_PUBLIC_API = 'https://store.steampowered.com/api/';
    private const string URL_GET_APP_DETAILS = 'appdetails';

    public function __construct()
    {
        $this->apiKey  = API_KEY_STEAM;
        $this->steamId = API_STEAM_ID;
    }

    /**
     * @return PlayerSummary|null Null on failure of request.
     */
    public function getPlayerSummary(): ?PlayerSummary
    {
        $url = sprintf('%s%s?key=%s&steamids=%s&format=json', self::BASE_URL, self::URL_GET_PLAYER_SUMMARIES, $this->apiKey, $this->steamId);

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
     * @return array<OwnedGame>
     */
    public function getOwnedGames(): array
    {
        $url = sprintf('%s%s?key=%s&steamid=%s&include_appinfo=1&format=json', self::BASE_URL, self::URL_GET_OWNED_GAMES, $this->apiKey, $this->steamId);

        try {
            $response = (new GuzzleClient())->request('GET', $url);
        }
        catch (Throwable $e) {
            return []; // Silent fail
        }

        $gamesData = json_decode($response->getBody(), true)['response']['games'] ?? [];

        $games = [];
        foreach ($gamesData as $gameData) {
            $ownedGame                = new OwnedGame($gameData);
            $games[$ownedGame->appId] = $ownedGame;
        }

        return $games;
    }

    /**
     * TODO Should be accepting multiple appIds and converting to custom class like OwnedGame.
     */
    public function getAppDetails(int $appId): object|null
    {
        $url = sprintf('%s%s?appids=%s', self::BASE_URL_PUBLIC_API, self::URL_GET_APP_DETAILS, $appId);

        try {
            $response = (new GuzzleClient())->request('GET', $url);
        }
        catch (Throwable $e) {
            return null; // Silent fail
        }

        return json_decode($response->getBody())?->$appId?->data;
    }
}