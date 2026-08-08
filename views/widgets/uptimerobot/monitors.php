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
            $statusCode  = $monitor['status'];
            $statusLabel = match ($statusCode) {
                0       => 'paused',
                1       => 'unchecked',
                2       => 'online',
                8       => 'seems down',
                9       => 'down',
                default => 'unknown'
            };
            $statusClass = match ($statusCode) {
                2       => 'success',
                0, 1, 8 => 'warning',
                9       => 'error',
                default => null
            };
            ?>
            <div class="website-wrapper <?= $statusClass ?>" onclick="window.open('<?= $monitor['url'] ?>', '_blank')">
                <p class="status"><?= $statusLabel ?></p>
                <p class="name"><?= $monitor['friendly_name'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>