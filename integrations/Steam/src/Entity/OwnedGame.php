<?php

namespace SSD\Integrations\Steam\Entity;

class OwnedGame extends SteamEntity
{
  public int $appId;
  public string $name;
  public ?int $playtime2Weeks;
  public int $playtimeForever;
  public int $playtimeWindowsForever;
  public int $playtimeMacForever;
  public int $playtimeLinuxForever;
  public string $imgIconUrl;
  public ?bool $hasCommunityVisibleStats;

  public function __construct(array $data)
  {
    $this->appId = (int)$data['appid'];
    $this->name = (string)$data['name'];
    $this->playtime2Weeks = (isset($data['playtime_2weeks']) ? (int)$data['playtime_2weeks'] : null);
    $this->playtimeForever = (int)$data['playtime_forever'];
    $this->playtimeWindowsForever = (int)$data['playtime_windows_forever'];
    $this->playtimeMacForever = (int)$data['playtime_mac_forever'];
    $this->playtimeLinuxForever = (int)$data['playtime_linux_forever'];
    $this->imgIconUrl = (string)$data['img_icon_url'];
    $this->hasCommunityVisibleStats = (isset($data['has_community_visible_stats']) ? (bool)$data['has_community_visible_stats'] : null);
  }

  /**
   * @param bool $inHours Hours will be rounded.
   * @param string $platform
   * @return int
   */
  public function getPlaytimeForever(bool $inHours = false, string $platform = 'all'): int
  {
    switch ($platform) {
      case 'windows':
        $playtime = $this->playtimeWindowsForever;
        break;
      case 'mac':
        $playtime = $this->playtimeMacForever;
        break;
      case 'linux':
        $playtime = $this->playtimeLinuxForever;
        break;
      default:
        $playtime = $this->playtimeForever;
        break;
    }

    if ($inHours) {
      return round($playtime / 60);
    }

    return $playtime;
  }
}