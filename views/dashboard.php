<?php
require_once __DIR__ . '/../php/session.php';
?>

<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>
  <title>SSD</title>
  <meta name="description" content="Statistics/Status Dashboard"/>
  <meta name="robots" content="noindex">
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<main>

  <div class="widget-container">
    <?php getWidget('steam', 'profileStatus'); ?>
  </div>

  <div class="widget-container">
    <?php getWidget('steam', 'top10PlayedGames'); ?>
  </div>

</main>

</body>
</html>
