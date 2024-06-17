<?php
    $q = [
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_type' => 'sponsor',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'is_hidden',
                'compare' => '!=',
                'value' => 1
            ),
        ),
    ];

	$specificIds = $args['specific_ids'] ?? [];

    if(!empty($specificIds)) {
        $q['post__in'] = $specificIds;
    }

    $sponsors = new WP_Query($q);
?>

<?php if ($sponsors->have_posts()) : ?>
    <section id="sponsors">
        <div class="container">
            <div class="section-id" id="sponzori"></div>
            <div class="swiper swiper-sponsors swiper-loading">
                <div class="swiper-wrapper">
                    <?php while ($sponsors->have_posts()) : $sponsors->the_post() ?>
                        <div class="swiper-slide d-flex justify-content-center align-items-center">
                            <a href="<?= get_field("link") ?? "#" ?>" class="sponsor--logo" target="_blank">
                                <img src="<?= get_field("logo") ?>" alt="<?= get_the_title() ?>">
                                <p><?= get_the_title() ?></p>
                            </a>
                        </div>
                    <?php endwhile; wp_reset_query(); ?>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>