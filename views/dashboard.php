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

  <?php getWidget('steam', 'top10PlayedGames'); ?>

</main>

</body>
</html>
