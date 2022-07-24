<?php
require_once __DIR__ . '/../php/session.php';
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
  <title>SSD Widgets</title>
  <meta name="description" content="Statistics/Status Dashboard"/>
  <meta name="robots" content="noindex">
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<main>

  <h1>Steam</h1>

  <div class="grid-container">

    <div class="grid-item grid-item-1-1">
      <?php getWidget('steam', 'profileStatus'); ?>
    </div>

  </div>

  <div class="grid-container">

    <div class="grid-item grid-item-1-2">
      <?php getWidget('steam', 'top10PlayedGames'); ?>
    </div>

    <div class="grid-item grid-item-1-2">
      <?php getWidget('steam', 'top10PlayedGamesWindows'); ?>
    </div>

    <div class="grid-item grid-item-1-2">
      <?php getWidget('steam', 'top10PlayedGamesMac'); ?>
    </div>

    <div class="grid-item grid-item-1-2">
      <?php getWidget('steam', 'top10PlayedGamesLinux'); ?>
    </div>

  </div>

</main>

</body>
</html>
