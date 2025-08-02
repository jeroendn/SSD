<?php

use SSD\Integrations\UptimeRobot\UptimeRobot;

$uptimeRobot = new UptimeRobot();
?>

<div id="widget-uptimerobot-monitors" class="widget uptimerobot-widget" refresh-rate="20000">
    <div class="widget-heading">
        <p class="widget-title">UptimeRobot</p>
    </div>
    <div class="widget-body">
        <?php $monitors = $uptimeRobot->getMonitors(); ?>
        <?php foreach ($monitors as $monitor): ?>
            <?php
            $httpCode    = $monitor['logs'][0]['reason']['code'];
            $firstChar   = $httpCode[0];
            $statusClass = match ($firstChar) {
                '2'      => 'success',
                '3'      => 'warning',
                '4', '5' => 'error',
                default  => null
            };
            if ($httpCode === '99') {
                $statusClass = 'warning';
                $httpCode    = 'paused';
            }
            ?>
            <div class="website-wrapper <?= $statusClass ?>" onclick="window.open('<?= $monitor['url'] ?>', '_blank')">
                <p class="status"><?= $httpCode ?></p>
                <p class="name"><?= $monitor['friendly_name'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>