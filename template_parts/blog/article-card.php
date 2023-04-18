<?php if (!empty($article = $args['article'])) : ?>

    <?php
        $flexDirection = $args['direction'] ?? "column";
        $classes = $args['classes'] ?? [];
        $size = $args['size'] ?? "small";
        $show_category = $args['show_category'] ?? false;
        $category = false;
        if($show_category) $category = getPostCategory($article);
    ?>

    <a href="<?= get_the_permalink($article) ?>" class="card article-card size-<?= $size ?> is-<?= $flexDirection ?> d-flex flex-<?= $flexDirection ?> <?= implode(" ", $classes) ?>">
        <div class="img-container">
            <img src="<?= get_the_post_thumbnail_url($article) ?>" alt="">
        </div>
        <div class="text-container">
            <div class="info-container">
                <?php if ($category && $show_category) : ?>
                    <div class="tag black small"><?= $category->name ?></div>
                <?php endif ?>
                <div class="dot-divided-info">
                    <span><?= get_the_date("d. M Y") ?></span>
                    <span><?= reading_time(get_the_ID()) ?> min</span>
                </div>
            </div>
            <h3 class="title"><?= get_the_title() ?></h3>
        </div>
    </a>
<?php endif ?>