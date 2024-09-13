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
            return $b['logs'][0]['reason']['code'] <=> $a['logs'][0]['reason']['code'];
        });

        return $monitors;
    }
}