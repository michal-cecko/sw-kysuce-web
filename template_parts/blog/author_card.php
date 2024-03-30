<?php
    $author_id = $args['author'] ?? 0; // Get the author ID from the query arguments
    $name = get_the_author_meta("first_name", $author_id) . " " . get_the_author_meta("last_name", $author_id);
    $nick = get_the_author_meta("nickname", $author_id);
    $desc = get_the_author_meta("description", $author_id); // Using get_the_author_meta to get the author description
?>
<div class="author-container">
    <div class="name"><?= $name ?></div>
    <div class="description"><?= $desc ?></div>
    <div class="image"><?= get_field("profile_picture", $author_id) ?></div>
    <a href="<?= get_site_url() ?>/blog?author=<?= $nick ?>" class="learn-more-btn">
        <?= __("Všetky články od autora", "swslovakia") ?>
        <span class="icon">
            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
        </span>
    </a>
</div>