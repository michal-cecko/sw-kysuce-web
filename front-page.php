<?php /* Template Name: Domovská stránka */ ?>
<?php get_header(); ?>


<!--   INTRO SECTION ----- START    -->

<?php

$args = [
    'posts_per_page' => 4,
    'post_status' => 'publish',
    'post_type' => 'post',
    'category__in' => [4], // ID kategórie "Súťaže"
];
$reports = new WP_Query($args);

$args = [
    'posts_per_page' => 4,
    'post_status' => 'publish',
    'post_type' => 'post',
];
if ($reports->have_posts()) $args['post__not_in'] = wp_list_pluck($reports->posts, 'ID');
$latestArticles = new WP_Query($args);

$args = [
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'post_type' => 'event',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'event_is_pinned_at_home',
            'value' => 1,
            'compare' => '=',
        ),
    ),
];
$pinnedEvent = new WP_Query($args);
?>

<section id="introSection">
    <div class="section-id" id="intro"></div>
    <div class="black-bg d-lg-block d-none"></div>
    <div class="container">
        <div class="upper-half d-flex justify-content-between">
            <div class="text-container">
                <h1 class="intro-heading">
                    <?= __("Slovenské združenie", "swslovakia") ?>
                    <br>
                    <?= __("pre", "swslovakia") ?>
                    <span class="labelled-text red big">
                            <span><?= __("street", "swslovakia") ?></span>
                        </span>
                    <span class="labelled-text white big img bottom-aligned">
                            <span>
                                <img src="<?= image_path() ?>/bar.png" alt="Bradlo">
                            </span>
                        </span>
                    <br>
                    <span class="labelled-text white big img top-aligned">
                            <span>
                                <img src="<?= image_path() ?>/rings.png" alt="Kruhy">
                            </span>
                        </span>
                    <span class="labelled-text red big">
                            <span><?= __("workout", "swslovakia") ?></span>
                        </span>
                </h1>
                <p class="secondary-text">
                    <?= get_field("intro_text") ?>
                </p>
            </div>
            <?php if ($latestArticles->have_posts()) : ?>
                <div class="d-lg-block d-none">
                    <div class="news-container">
                         <span class="tag red small">
                            <?= svgIcon(icon_path(false) . "icon-flame.svg", ['class' => ['mr-1']]) ?>
                            <?= __("Novinky", "swslovakia") ?>
                        </span>
                        <?php $i = 0;
                        while ($latestArticles->have_posts() && $i < 2) : $latestArticles->the_post() ?>
                            <a href="#" class="card">
                                <h2><?= get_the_title() ?></h2>
                                <div class="dot-divided-info">
                                    <span><?= get_the_date("d. M Y") ?></span>
                                    <?php if ($readingTime = reading_time(get_the_ID())) : ?>
                                        <span><?= $readingTime ?> min</span>
                                    <?php endif ?>
                                </div>
                            </a>
                            <?php $i++; endwhile ?>
                    </div>
                </div>
                <?php wp_reset_query(); endif ?>
        </div>
        <div class="lower-half">
            <span class="bg-text">workout</span>
            <?php if ($pinnedEvent->have_posts()) : $pinnedEvent->the_post(); ?>
                <a href="<?= get_the_permalink() ?>" class="pinned-event">
                    <div class="date">
                        <div class="day">
                            <div class="day-before"><?= date("d", strtotime("-1 day", strtotime(get_the_date("Y-m-d")))) ?></div>
                            <div class="day-current"><?= get_the_date("d") ?></div>
                        </div>
                        <div class="month"><?= ucfirst(get_the_date("M")) ?></div>
                    </div>
                    <div class="text">
                        <div class="title"><?= get_the_title() ?></div>
                        <button class="learn-more-btn">
                            <?= __("Zistiť viac", "swslovakia") ?>
                            <span class="icon">
                                    <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                                </span>
                        </button>
                    </div>
                </a>
                <?php wp_reset_query(); endif ?>
            <?php if ($image = get_field("home_intro_image")) : ?>
                <div class="image d-lg-block d-none">
                    <img src="<?= $image ?>" alt="Intro obrázok">
                </div>
            <?php endif ?>
        </div>
    </div>
</section>


<!--   INTRO SECTION ----- END    -->


<!--   BLOG SECTION ----- START    -->


<section id="blogSection">
    <div class="container">
        <div class="section-id" id="blog"></div>
        <h1 class="heading"><?= get_field("home_blog_heading") ?></h1>
        <?php if ($sub = get_field("home_blog_subheading")) : ?>
            <p class="secondary-text mt-3"><?= $sub ?></p>
        <?php endif ?>
        <div class="blog-container row">
            <div class="first-article col-lg-6">
                <?php $i = 0;
                if ($latestArticles->have_posts()) :
                    $latestArticles->the_post();
                    get_template_part("template_parts/blog/article-card", "", [
                        'direction' => 'column',
                        'article' => get_post(),
                        'size' => 'big',
                        'show_category' => true
                    ]);
                endif ?>
            </div>
            <?php if ($latestArticles->post_count > 1) : ?>
                <div class="more-articles col-lg-6 red-scrollbar">
                    <div class="row ">
                        <?php while ($latestArticles->have_posts() && $i < 3) : ?>
                            <div class="col-lg-12 col-4 mb-lg-4 mb-0">
                                <?php $latestArticles->the_post();
                                get_template_part("template_parts/blog/article-card", "", [
                                    'direction' => 'row',
                                    'article' => get_post(),
                                    'size' => 'small',
                                    'show_category' => true,
                                    'classes' => ['lg-column']
                                ]); ?>
                            </div>
                        <?php $i++; endwhile;
                        wp_reset_query(); ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="col-12">
                <a href="<?= get_site_url() ?>/blog" class="learn-more-btn">
                    <?= __("Zobraziť viac", "swslovakia") ?>
                    <span class="icon">
                            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                        </span>
                </a>
            </div>
        </div>

        <div class="labels d-flex align-items-center">
                <span class="labelled-text big black mr-3">
                    <span>
                    <?= __("články", "swslovakia") ?>
                    </span>
                </span>
            <span class="labelled-text blue img big mr-3">
                    <img src="<?= image_path() ?>/stalks.png" alt="Stalky">
                </span>
            <span class="labelled-text big black">
                    <span>
                    <?= __("tutoriály", "swslovakia") ?>
                    </span>
                </span>
        </div>
    </div>
</section>


<!--   BLOG SECTION ----- END    -->


<!--   REPORTS SECTION ----- START    -->


<section id="reportsSection">
    <div class="container">
        <div class="section-id" id="reporty_zo_sutazi"></div>
        <h1 class="heading"><?= get_field("home_reports_heading") ?></h1>
        <span class="bg-text d-md-block d-none">súťaže</span>
        <?php if ($reports->have_posts()) : ?>
            <div class="reports-container red-scrollbar">
                <div class="row">
                    <?php while ($latestArticles->have_posts()) : $latestArticles->the_post(); ?>
                        <div class="col-3">
                            <?php get_template_part("template_parts/blog/article-card", "", [
                                'direction' => 'column',
                                'article' => get_post(),
                                'size' => 'small',
                                'show_category' => false
                            ]); ?>
                        </div>
                    <?php endwhile ?>
                </div>
            </div>
        <?php endif ?>

        <a href="<?= get_site_url() ?>/blog" class="learn-more-btn mt-5">
            <?= __("Zobraziť viac", "swslovakia") ?>
            <span class="icon">
                <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
            </span>
        </a>

        <div class="labels">
            <span class="labelled-text black big">
                <span>
                <?= __("súťaže", "swslovakia") ?>
                </span>
            </span>
            <span class="labelled-text blue img big">
                    <img src="<?= image_path() ?>/plates.png" alt="Švihadlo">
                </span>
            <span class="labelled-text red big">
                <span>
                <?= __("reporty", "swslovakia") ?>
                </span>
            </span>
        </div>
    </div>

</section>


<!--   REPORTS SECTION ----- END    -->


<!--  ABOUT CARD ---- START  -->

<?php get_template_part("template_parts/sections/about-card"); ?>

<!--  ABOUT CARD ---- END  -->


<div class="bg-blue-gradient">

    <!--   LATEST EVENTS SECTION ----- START    -->

    <?php get_template_part("template_parts/sections/latest-events"); ?>

    <!--   LATEST EVENTS SECTION ----- END    -->

    <?php get_footer(); ?>
</div>
