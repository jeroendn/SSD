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
        $monitors = $this->client->getMonitors()['monitors'] ?? [];

        usort($monitors, function ($a, $b) {
            return $b['status'] <=> $a['status'];
        });

        return $monitors;
    }
}