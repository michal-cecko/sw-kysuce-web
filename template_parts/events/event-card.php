<?php if (!empty($event = $args['event'])) : ?>

<?php
$classes = $args['classes'] ?? [];
$showRegistrationLink = $args['show_registration_link'] ?? false;
$tags = wp_get_post_terms(get_the_ID(), 'event-tag');
$formLink = get_field("register_form");
$externalLink = get_field("register_link");
$link = !empty($formLink) ? get_the_permalink() : $externalLink;
?>
<?php if ($showRegistrationLink) : ?>
<a href="<?= $link ?>" class="card event-card d-flex flex-column <?= implode(" ", $classes) ?>">
    <?php else : ?>
    <a class="card event-card d-flex flex-column <?= implode(" ", $classes) ?>">
        <?php endif ?>

        <?php if (!empty($tags)) : ?>
            <div class="tags">
                <?php foreach ($tags as $tag) : ?>
                    <div class="tag outlined white small"><?= $tag->name ?></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <h3 class="title"><?= get_the_title() ?></h3>
        <div class="where-when-container">
            <div class="part">
                <div class="icon">
                    <?= svgIcon(icon_path(false) . "/icon-calendar_filled.svg") ?>
                </div>
                <p><?= getEventDate($event->ID) ?></p>
            </div>
            <div class="part">
                <div class="icon">
                    <?= svgIcon(icon_path(false) . "/icon-location.svg") ?>
                </div>
                <p><?= get_field("place_name") ?></p>
            </div>
        </div>

        <?php if ($showRegistrationLink) : ?>
            <button class="btn btn--medium btn--red">
                <span>
                    Registrácia
                    <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg", ['class' => ['ml-1']]) ?>
                </span>
            </button>
        <?php endif ?>
        <?php if ($showRegistrationLink) : ?>
    </a>
<?php else : ?>
    </div>
<?php endif ?>
    <?php endif ?>
