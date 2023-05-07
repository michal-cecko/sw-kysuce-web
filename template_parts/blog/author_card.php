<?php
    $authorID = $args['author'] ?? 0;
    $name = get_the_author_meta("first_name", $authorID) . " " . get_the_author_meta("last_name", $authorID);
    $nick = get_the_author_meta("nickname", $authorID);
    $desc = get_author_description_by_id($authorID);
?>
<div class="author-container">
    <div class="name"><?= $name ?></div>
    <div class="description"><?= $desc ?></div>
    <div class="image"><?= get_field("author_image", $authorID) ?></div>
    <a href="<?= get_site_url() ?>/blog?author=<?= $nick ?>" class="learn-more-btn">
        <?= __("Všetky články od autora", "swslovakia") ?>
        <span class="icon">
            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
        </span>
    </a>
</div>