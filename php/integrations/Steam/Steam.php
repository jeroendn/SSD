<?php

namespace SSD\Integrations\Steam;

final class Steam
{
  private Client $client;

  public function __construct()
  {
    $this->client = new Client;
  }

  /**
   * @return array
   */
  public function getTop10PlayedGames(): array
  {
    $games = $this->client->getOwnedGames();

    $this->sortBySubarrayValue($games, 'playtime_forever', true);

    $topTenGames = [];

    for ($i = 0; $i < 10; $i++) {
      $topTenGames[] = $games[$i];
    }

    return $topTenGames;
  }

  /**
   * TODO Make this a generic function?
   * @param array $array
   * @param string $subarrayIndex
   * @param bool $reverse
   * @return void
   */
  private function sortBySubarrayValue(array &$array, string $subarrayIndex, bool $reverse = false): void
  {
    if ($reverse) {
      usort($array, function ($a, $b) {
        return $b['playtime_forever'] <=> $a['playtime_forever'];
      });
    }
    else {
      usort($array, function ($a, $b) {
        return $a['playtime_forever'] <=> $b['playtime_forever'];
      });
    }
  }
}