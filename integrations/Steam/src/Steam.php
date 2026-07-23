<?php

namespace SSD\Integrations\Steam;

use jeroendn\PhpHelpers\Helper\ArrayHelper;
use SSD\Integrations\Steam\Entity\OwnedGame;

final readonly class Steam
{
  private Client $client;

  public function __construct()
  {
    $this->client = new Client;
  }

  /**
   * @return OwnedGame[]
   */
  public function getTop10PlayedGames(string $platform = 'all'): array
  {
    $games = $this->client->getOwnedGames();

    $playtimePlatform = match ($platform) {
        'windows' => 'playtimeWindowsForever',
        'mac' => 'playtimeMacForever',
        'linux' => 'playtimeLinuxForever',
        default => 'playtimeForever',
    };

    ArrayHelper::sortByProperty($games, $playtimePlatform, false);

    $topTenGames = [];

    for ($i = 0; $i < 10; $i++) {
      $topTenGames[] = $games[$i];
    }

    return $topTenGames;
  }

  /**
   * @return OwnedGame[]
   */
  public function getTop10PlayedGamesLast2Weeks(): array
  {
    $games = $this->client->getOwnedGames();

    ArrayHelper::sortByProperty($games, 'playtime2Weeks', false);

    $topTenGames = [];

    for ($i = 0; $i < 10; $i++) {
      if ($games[$i]->playtime2Weeks == 0) { // Do not show games with 0 playtime
        continue;
      }

      $topTenGames[] = $games[$i];
    }

    return $topTenGames;
  }
}