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
   * TODO Add platform parameter support instead of only total.
   * @return Game[]
   */
  public function getTop10PlayedGames(): array
  {
    $games = $this->client->getOwnedGames();

    $this->sortArrayByProperty($games, 'playtimeForever', true);

    $topTenGames = [];

    for ($i = 0; $i < 10; $i++) {
      $topTenGames[] = $games[$i];
    }

    return $topTenGames;
  }

  /**
   * TODO Make this a generic function?
   * Sort an array of objects by a property of the object.
   * Does not work on multidimensional arrays.
   * @param array $array
   * @param string $property
   * @param bool $reverse
   * @return void
   */
  private function sortArrayByProperty(array &$array, string $property, bool $reverse = false): void
  {
    if ($reverse) {
      usort($array, function ($a, $b) use ($property) {
        return $b->$property <=> $a->$property;
      });
    }
    else {
      usort($array, function ($a, $b) use ($property) {
        return $a->$property <=> $b->$property;
      });
    }
  }
}