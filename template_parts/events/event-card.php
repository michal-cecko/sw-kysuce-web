<?php if (!empty($event = $args['event'])) : ?>

<?php
$classes = $args['classes'] ?? [];
$showRegistrationLink = $args['show_registration_link'] ?? false;
$showDescription = $args['show_description'] ?? false;
$tags = wp_get_post_terms($event->ID, 'event-tag');
$formLink = get_field("event_register_form", $event->ID);
$externalLink = get_field("event_register_link", $event->ID);
$desc = get_field("event_description", $event->ID);
$link = !empty($formLink) ? get_the_permalink($event->ID) : $externalLink;
?>
<?php if ($showRegistrationLink) : ?>
<a href="<?= $link ?>" class="card event-card d-flex flex-column <?= implode(" ", $classes) ?>">
    <?php else : ?>
    <div class="card event-card d-flex flex-column <?= implode(" ", $classes) ?>">
        <?php endif ?>

        <?php if (!empty($tags)) : ?>
            <div class="tags mb-4">
                <?php foreach ($tags as $tag) : ?>
                    <div class="tag outlined white small"><?= $tag->name ?></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <h3 class="title"><?= get_the_title($event->ID) ?></h3>
        <?php if ( $showDescription && !empty($desc) ) : ?>
            <p class="description"><?= $desc ?></p>
        <?php endif ?>
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
                <p><?= get_field("event_place", $event->ID) ?></p>
            </div>
        </div>

        <?php if ($showRegistrationLink) : ?>
        <button class="btn btn--medium btn--red">
            <span>
                Registrácia
                <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg", ['class' => ['ml-1']]) ?>
            </span>
        </button>
    </a>
<?php else : ?>
    </div>
<?php endif ?>
    <?php endif ?>
