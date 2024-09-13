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
            <div class="">
                <p><?= $monitor['friendly_name'] ?></p>
                <p><?= $monitor['logs'][0]['reason']['code'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>