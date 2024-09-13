<?php

namespace SSD\Integrations\UptimeRobot;

use GuzzleHttp\Client as GuzzleClient;
use Throwable;

final class Client
{
    private string $apiKey;
    private string $baseUrl = 'https://api.uptimerobot.com';

    private string $urlGetMonitors = '/v2/getMonitors';

    public function __construct()
    {
        $this->apiKey = API_KEY_UPTIME_ROBOT_READ_ONLY;
    }

    public function getMonitors(): ?array
    {
        $url = $this->baseUrl . $this->urlGetMonitors;

        $options = [
            'form_params' => [
                'api_key' => $this->apiKey,
                'format' => 'json',
                'logs' => '1'
            ]
        ];

        try {
            $response = (new GuzzleClient())->request('POST', $url, $options);
        }
        catch (Throwable $e) {
            return null; // Silent fail
        }

        return json_decode($response->getBody(), true) ?? [];
    }
}