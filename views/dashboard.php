<?php
require_once __DIR__ . '/../php/session.php';
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
    <title>SS Dashboard</title>
    <meta name="description" content="Statistics/Status Dashboard"/>
    <?php require_once __DIR__ . '/elements/head.php' ?>
</head>

<body>

<main>

    <?php require_once __DIR__ . '/elements/hiddenNav.php' ?>

    <div class="grid-group-vertical">

        <div class="grid-item grid-item-2-1">
            <?php getWidget('steam', 'profileStatus'); ?>
        </div>

        <div class="grid-item grid-item-2-1">
            <?php getWidget('spotify', 'player'); ?>
        </div>

        <div class="grid-item grid-item-2-1"></div>

        <div class="grid-item grid-item-2-2">
            <?php getWidget('buienradar', 'country'); ?>
        </div>

        <div class="grid-item grid-item-2-2">
            <?php getWidget('buienradar', 'city'); ?>
        </div>

    </div>

    <div class="grid-group-vertical">

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'playtimeLast2Weeks'); ?>
        </div>

        <div class="grid-item grid-item-2-3">
            <?php getWidget('uptimerobot', 'monitors'); ?>
        </div>

    </div>

    <div class="grid-group-vertical">

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGames'); ?>
        </div>

    </div>

    <div class="grid-group-vertical">

        <div class="grid-item grid-item-2-3">
            <?php getWidget('steam', 'top10PlayedGamesGlobal'); ?>
        </div>

    </div>

</main>

<?php require_once __DIR__ . '/elements/js.php'; ?>

</body>
</html>
