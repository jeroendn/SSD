<?php

use SSD\Integrations\Tweakers\Tweakers;

$tweakers = new Tweakers();
?>

<div id="widget-tweakers-newsfeed" class="widget tweakers-widget" refresh-rate="50000">
    <div class="widget-heading">
        <p class="widget-title">Tweakers</p>
    </div>
    <div class="widget-body">
        <?php $posts = $tweakers->getLast10Items(); ?>
        <?php foreach ($posts as $post): ?>
            <div class="link-wrapper" onclick="window.open('<?= $post->url ?>', '_blank')">
                <p class="title"><?= $post->title ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>