<?php
require_once __DIR__ . '/../php/session.php';
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
  <title>SS Dashboard</title>
  <meta name="description" content="Statistics/Status Dashboard"/>
  <meta name="robots" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="/fonts/fontawesome/css/all.css">
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<main>

  <div class="grid-group-vertical">
    <div class="grid-item grid-item-1-1">
      <?php getWidget('steam', 'profileStatus'); ?>
    </div>

    <div class="grid-item grid-item-1-1">
      <?php getWidget('steam', 'playtimeLast2Weeks'); ?>
    </div>
  </div>

  <div class="grid-item grid-item-1-2">
    <?php getWidget('steam', 'top10PlayedGames'); ?>
  </div>

</main>

<?php require_once __DIR__.'/elements/js.php'; ?>

</body>
</html>
