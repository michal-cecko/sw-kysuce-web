
<?php $color = $args['color'] ?? 'black'; ?>

<div class="socials socials-<?= $color ?> d-flex align-items-center flex-wrap gap-3">
    <?php if ($fb = get_field("socials_facebook", "options")) : ?>
        <a href="<?= $fb ?>" class="social facebook">
            <?= svgIcon(icon_path(false) . "/icon-facebook.svg") ?>
        </a>
    <?php endif ?>
    <?php if ($ig = get_field("socials_instagram", "options")) : ?>
        <a href="<?= $ig ?>" class="social instagram">
            <?= svgIcon(icon_path(false) . "/icon-instagram.svg") ?>
        </a>
    <?php endif ?>
</div>