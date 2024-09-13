<?php

namespace SSD\Integrations\UptimeRobot;

final class UptimeRobot
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function getMonitors(): array
    {
        $monitors = $this->client->getMonitors();

        return $monitors['monitors'] ?? [];
    }
}