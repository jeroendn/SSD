<?php

namespace SSD\Integrations\Steam\Entity;

use DateTimeImmutable;

class PlayerSummary extends SteamEntity
{
  public string $steamId;
  public string $personaName;
  public string $profileUrl;
  public string $avatar;
  public string $avatarMedium;
  public string $avatarFull;
  public int $personaState;
  public int $communityVisibilityState;
  public bool $profileState;
  public DateTimeImmutable $lastLogoff;
  public bool $commentPermission;
  public ?string $realName;
  public ?string $primaryClanId;
  public ?DateTimeImmutable $timeCreated;
  public ?int $gameId;
  public ?string $gameServerIp;
  public ?string $gameExtraInfo;
  public ?string $localCountryCode;
  public ?string $localStateCode;
  public ?string $localCityId;

  public const PERSONA_STATES = [
    0 => 'Offline',
    1 => 'Online',
    2 => 'Busy',
    3 => 'Away',
    4 => 'Snooze',
    5 => 'Looking to trade',
    6 => 'Looking to play'
  ];

  public function __construct(array $data)
  {
    $this->steamId = (string)$data['steamid'];
    $this->personaName = (string)$data['personaname'];
    $this->profileUrl = (string)$data['profileurl'];
    $this->avatar = (string)$data['avatar'];
    $this->avatarMedium = (string)$data['avatarmedium'];
    $this->avatarFull = (string)$data['avatarfull'];
    $this->personaState = (int)$data['personastate'];
    $this->communityVisibilityState = (int)$data['communityvisibilitystate'];
    $this->profileState = (bool)$data['profilestate'];
    $this->lastLogoff = (new DateTimeImmutable)->setTimeStamp($data['lastlogoff']);
    $this->commentPermission = (isset($data['commentpermission']));
    $this->realName = (isset($data['realname']) ? (string)$data['realname'] : null);
    $this->primaryClanId = (isset($data['primaryclanid']) ? (string)$data['primaryclanid'] : null);
    $this->timeCreated = (isset($data['timecreated']) ? (new DateTimeImmutable)->setTimeStamp($data['timecreated']) : null);
    $this->gameId = (isset($data['gameid']) ? (int)$data['gameid'] : null);
    $this->gameServerIp = (isset($data['gameserverip']) ? (string)$data['gameserverip'] : null);
    $this->gameExtraInfo = (isset($data['gameextrainfo']) ? (string)$data['gameextrainfo'] : null);
    $this->localCountryCode = (isset($data['loccountrycode']) ? (string)$data['loccountrycode'] : null);
    $this->localStateCode = (isset($data['locstatecode']) ? (string)$data['locstatecode'] : null);
    $this->localCityId = (isset($data['loccityid']) ? (string)$data['loccityid'] : null);
  }

  /**
   * @return bool
   */
  public function getIsProfilePublic(): bool
  {
    return ($this->communityVisibilityState === 3);
  }
}