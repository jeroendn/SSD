<?php

namespace SSD\Integrations\Steam\Entity;

class App extends SteamEntity
{
    public function __construct(
        public int $appId,
        public string $name,
        public string $iconHash,
    ) {}

    /**
     * @return string|null Null when Steam has no community icon for this app.
     */
    public function getIconUrl(): ?string
    {
        if ($this->iconHash === '') {
            return null;
        }

        return sprintf('https://media.steampowered.com/steamcommunity/public/images/apps/%d/%s.jpg', $this->appId, $this->iconHash);
    }
}
