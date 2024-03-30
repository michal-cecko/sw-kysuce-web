<?php

$args = [
    'posts_per_page' => 7,
    'post_status' => 'publish',
    'post_type' => 'event',
    'meta_query' => array(
        array(
            'key' => 'event_article_link',
            'compare' => '!=',
            'value' => null
        ),
    ),
];
$events = new WP_Query($args);

?>

<?php if ($events->have_posts()) : ?>
<section id="latestEventsSection">
    <div class="container">
        <div class="section-id" id="posledne_eventy"></div>
        <div class="row">
            <div class="section-title-container col-md-5">
                <h1 class="heading">Posledné eventy</h1>
                <div class="slider-arrows d-md-flex d-none">
                    <div class="event-arrow slider-arrow prev">
                        <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                    </div>
                    <div class="event-arrow slider-arrow next">
                        <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                    </div>
                </div>
            </div>
            <div class="events-container col-md-7">
                <div class="swiper swiper-latest-events">
                    <div class="swiper-wrapper swiper-loading">
                        <?php while ($events->have_posts()) : $events->the_post() ?>
                            <?php $article = get_field("event_article_link"); ?>
                            <div class="swiper-slide">
                                <a href="<?= get_the_permalink($article) ?>" class="card event-card flex-column">
                                    <div class="img-container">
                                        <img src="<?= get_the_post_thumbnail_url($article) ?>" alt="">
                                    </div>
                                    <h5 class="title"><?= get_the_title($article) ?></h5>
                                </a>
                            </div>
                        <?php endwhile; wp_reset_query(); ?>
                    </div>
                </div>
            </div>
            <div class="slider-arrows mt-4 d-md-none d-flex">
                <div class="event-arrow slider-arrow prev">
                    <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                </div>
                <div class="event-arrow slider-arrow next">
                    <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                </div>
            </div>
        </div>
        <div class="bg-text">compets</div>
    </div>
</section>
<?php endif ?>