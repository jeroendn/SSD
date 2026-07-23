<?php

namespace SSD\Integrations\UptimeRobot;

final readonly class UptimeRobot
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function getMonitors(): array
    {
        $monitors = $this->client->getMonitors()['monitors'] ?? [];

        usort($monitors, fn($a, $b) => $b['status'] <=> $a['status']);

        return $monitors;
    }
}