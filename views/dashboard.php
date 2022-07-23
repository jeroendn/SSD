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

<main id="content" class="left-float">

  <?php include __DIR__ . '/elements/steamtest.php'; ?>

  <section>

    <div class="textbox textbox-about">

      <div class="version-info">
        <h3>Version info</h3>
      </div>

    </div>
  </section>

  <section id="article-submit">
    <div class="heading">
      <h2>Submit an article</h2>
    </div>
  </section>

  <section id="wallpapers">
    <div class="heading">
      <h2>Wallpapers</h2>
    </div>

    <div class="textbox textbox-about">
      <p>Here you can download some of the phone wallpapers I created. Simply by clicking on the image you want to download.</p>
    </div>
  </section>

</main>

</body>
</html>
