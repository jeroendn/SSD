<?php

namespace SSD\Integrations\Steam;

use SSD\Integrations\Steam\Entity\Game;

final class Steam
{
  private Client $client;

  public function __construct()
  {
    $this->client = new Client;
  }

  /**
   * @return Game[]
   */
  public function getTop10PlayedGames(string $platform = 'all'): array
  {
    $games = $this->client->getOwnedGames();

    switch ($platform) {
      case 'windows':
        $playtimePlatform = 'playtimeWindowsForever';
        break;
      case 'mac':
        $playtimePlatform = 'playtimeMacForever';
        break;
      case 'linux':
        $playtimePlatform = 'playtimeLinuxForever';
        break;
      default:
        $playtimePlatform = 'playtimeForever';
        break;
    }

    sortArrayByProperty($games, $playtimePlatform, true);

    $topTenGames = [];

    for ($i = 0; $i < 10; $i++) {
      $topTenGames[] = $games[$i];
    }

    return $topTenGames;
  }
}