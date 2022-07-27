<?php

namespace SSD\Integrations\Spotify;

final class Helper
{
  /**
   * @param int $milliseconds
   * @return int
   */
  public static function msToMin(int $milliseconds): int
  {
    return $milliseconds / 60000;
  }

  /**
   * @param int $milliseconds
   * @return string
   */
  public static function msToReadableString(int $milliseconds): string
  {
    $totalSeconds = $milliseconds / 1000;
    $totalMinutes = $totalSeconds / 60;

    $minutes = floor($totalMinutes);
    $seconds = floor($totalSeconds - ($minutes * 60));

    if ($seconds < 10) {
      $seconds = '0' . $seconds;
    }

    return "$minutes:$seconds";
  }
}