<?php
require_once __DIR__ . '/../php/session.php';
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
    <title>SSD Widgets</title>
    <meta name="description" content="Statistics/Status Dashboard"/>
    <?php require_once __DIR__ . '/elements/head.php' ?>
</head>

<body>

<main>

    <?php require_once __DIR__ . '/elements/hiddenNav.php' ?>

    <h1>Spotify</h1>

    <div class="grid-container">
        <div class="grid-item grid-item-2-1">
            <?php getWidget('spotify', 'player'); ?>
        </div>
    </div>

    <h1>Steam</h1>

    <div class="grid-container">
        <div class="grid-item grid-item-2-1">
            <?php getWidget('steam', 'profileStatus'); ?>
        </div>
    </div>

    <div class="grid-container">
        <div class="grid-item grid-item-2-2">
            <?php getWidget('steam', 'playtimeLast2Weeks'); ?>
        </div>

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'playtimeLast2Weeks'); ?>
        </div>
    </div>


    <div class="grid-container">
        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGames'); ?>
        </div>

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGamesWindows'); ?>
        </div>

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGamesMac'); ?>
        </div>

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGamesLinux'); ?>
        </div>
    </div>

    <div class="grid-container">
        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGamesGlobal'); ?>
        </div>
    </div>

    <h1>Buienradar</h1>

    <div class="grid-container">
        <div class="grid-item grid-item-2-2">
            <?php getWidget('buienradar', 'country'); ?>
        </div>

        <div class="grid-item grid-item-2-2">
            <?php getWidget('buienradar', 'city'); ?>
        </div>
    </div>

    <h1>UptimeRobot</h1>

    <div class="grid-container">
        <div class="grid-item grid-item-2-3">
            <?php getWidget('uptimerobot', 'monitors'); ?>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/elements/js.php'; ?>

</body>
</html>
