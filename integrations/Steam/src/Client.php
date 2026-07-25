<?php

namespace SSD\Integrations\Steam;

use GuzzleHttp\Client as GuzzleClient;
use SSD\Integrations\Steam\Entity\App;
use SSD\Integrations\Steam\Entity\OwnedGame;
use SSD\Integrations\Steam\Entity\PlayerSummary;
use Throwable;

final readonly class Client
{
    private string $apiKey;
    private string $steamId;
    private const string        BASE_URL                  = 'https://api.steampowered.com/';
    private const string        URL_GET_OWNED_GAMES       = 'IPlayerService/GetOwnedGames/v1/';
    private const string        URL_GET_PLAYER_SUMMARIES  = 'ISteamUser/GetPlayerSummaries/v2/';
    private const string        URL_GET_APPS              = 'ICommunityService/GetApps/v1/';
    private const string        URL_GET_MOST_PLAYED_GAMES = 'ISteamChartsService/GetGamesByConcurrentPlayers/v1/';

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
            $response = new GuzzleClient()->request('GET', $url);
        } catch (Throwable) {
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
        $url = sprintf('%s%s?key=%s&steamid=%s&include_appinfo=1&include_played_free_games=1&format=json', self::BASE_URL, self::URL_GET_OWNED_GAMES, $this->apiKey, $this->steamId);

        try {
            $response = new GuzzleClient()->request('GET', $url);
        } catch (Throwable) {
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
     * Fetches name and community icon for any apps, owned or not. No API key required.
     *
     * @param int[] $appIds
     * @return array<int, App> Keyed by appId. Empty on failure of request.
     */
    public function getApps(array $appIds): array
    {
        if ($appIds === []) {
            return [];
        }

        $url = sprintf('%s%s?%s', self::BASE_URL, self::URL_GET_APPS, http_build_query(['appids' => array_values($appIds)]));

        try {
            $response = new GuzzleClient()->request('GET', $url);
        } catch (Throwable) {
            return []; // Silent fail
        }

        $apps = [];
        foreach ($this->decodeResponseItems((string) $response->getBody(), 'apps') as $appData) {
            $appId    = $appData['appid'] ?? null;
            $name     = $appData['name'] ?? null;
            $iconHash = $appData['icon'] ?? null;

            if (!is_int($appId)) {
                continue;
            }

            $apps[$appId] = new App(
                $appId,
                is_string($name) ? $name : 'App ' . $appId,
                is_string($iconHash) ? $iconHash : '',
            );
        }

        return $apps;
    }

    /**
     * Current top games on Steam by concurrent player count. No API key required.
     *
     * @return array<int, int> Player count keyed by appId, ordered by rank. Empty on failure of request.
     */
    public function getMostPlayedGames(int $limit = 10): array
    {
        try {
            $response = new GuzzleClient()->request('GET', self::BASE_URL . self::URL_GET_MOST_PLAYED_GAMES);
        } catch (Throwable) {
            return []; // Silent fail
        }

        $mostPlayed = [];
        foreach ($this->decodeResponseItems((string) $response->getBody(), 'ranks') as $rank) {
            $appId       = $rank['appid'] ?? null;
            $playerCount = $rank['concurrent_in_game'] ?? null;

            if (!is_int($appId) || !is_int($playerCount)) {
                continue;
            }

            $mostPlayed[$appId] = $playerCount;

            if (count($mostPlayed) === $limit) {
                break;
            }
        }

        return $mostPlayed;
    }

    /**
     * @return list<array<mixed>> The item list under the 'response' wrapper, empty on unexpected JSON.
     */
    private function decodeResponseItems(string $json, string $key): array
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return [];
        }

        $responseData = $data['response'] ?? null;
        if (!is_array($responseData)) {
            return [];
        }

        $items = $responseData[$key] ?? null;
        if (!is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, is_array(...)));
    }
}
