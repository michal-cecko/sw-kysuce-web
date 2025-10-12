<?php
/* Template Name: Events */
get_header();

$args = [
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_type' => 'event',
    'meta_query' => [
        'relation' => "OR",
        [
            'key' => 'event_start',
            'value' => date('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE'
        ],
        [
            'relation' => 'AND',
            [
                'key' => 'event_start',
                'value' => date('Y-m-d'),
                'compare' => '<',
                'type' => 'DATE'
            ],
            [
                'key' => 'event_is_public_after_start',
                'value' => '1',
                'compare' => '='
            ]
        ]
    ],
    'orderby' => 'meta_value',
    'meta_key' => 'event_start',
    'order' => 'ASC'
];
$upcomingEvents = new WP_Query($args);
?>

    <div id="events">
        <!--   INTRO SECTION TEXT ----- START    -->

        <section id="eventsIntro" class="bg-blue-gradient-side-mirrored">
            <div class="section-id" id="intro"></div>
            <div class="container">
                <span class="bg-text"><?= date("Y") ?></span>
                <div class="text-container">
                    <h1 class="intro-heading">
                        <?= __("Plán podujatí združenia", "swslovakia") ?>
                        <span class="labelled-text red big d-none d-md-inline-block">
                            <span><?= __("Street workout kysuce", "swslovakia") ?></span>
                        </span>
                        <span class="labelled-text red big d-inline-block d-md-none">
                            <span><?= __("Street workout", "swslovakia") ?></span>
                        </span>
                        <span class="labelled-text red big d-inline-block d-md-none">
                            <span><?= __("Kysuce", "swslovakia") ?></span>
                        </span>
                    </h1>
                    <a href="#nadchadzajuce-podujatia" class="learn-more-btn down bigger mb-md-0 mb-5">
                        <span class="icon">
                            <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <!--   INTRO SECTION TEXT ----- END    -->


        <!--   THUMB ----- START    -->
        <?php if ($img = get_field("events_image")) : ?>
            <section id="thumbSection">
                <a data-fslightbox="thumb" href="<?= $img ?>">
                    <img src="<?= $img ?>" alt="Thumbnail obrázok">
                </a>
            </section>
        <?php endif ?>
        <!--   THUMB ----- END    -->


        <!--   UPCOMING EVENTS ----- START    -->
        <?php if ($upcomingEvents->have_posts()) : ?>
            <section id="upcomingEvents" class="bg-blue-gradient-side">
                <div class="section-id" id="nadchadzajuce-podujatia"></div>
                <div class="container">
                    <div class="row">
                        <?php while ($upcomingEvents->have_posts()) : $upcomingEvents->the_post() ?>
                            <div class="col-lg-4 col-md-6">
                                <?php get_template_part("template_parts/blog/article-card", "", [
                                    'direction' => 'column',
                                    'article' => get_post(),
                                    'size' => 'small',
                                    'show_category' => false
                                ]); ?>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>
        <?php endif ?>

        <!--   UPCOMING EVENTS ----- END    -->


        <?php
        // Set up the query arguments
        $year = date("Y");
        $args = [
            'post_type' => 'event',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_key' => 'event_start',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => [
                "relation" => "AND",
                [
                    'key' => 'event_start',
                    'value' => [$year . '-01-01', $year . '-12-31'],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
                [
                    'key' => 'event_start',
                    'value' => date('Y-m-d'),
                    'compare' => '<',
                    'type' => 'DATE'
                ],
                [
                    'key' => 'event_article_link',
                    'value' => '',
                    'compare' => '!='
                ]
            ],
        ];

        $post_ids = get_posts($args);

        // Extract the years from the 'event_start' field
        $years = [];
        foreach ($post_ids as $post_id) {
            $date_start = get_field('event_start', $post_id);
            $year = date('Y', strtotime($date_start));
            if (!in_array($year, $years)) {
                $years[] = $year;
            }
        }

        rsort($years);
        ?>

        <div class="d-none" id="pastEventsData" data-years="<?= implode(",", $years) ?>"></div>

        <!--   PAST EVENTS ----- START    -->
        <?php if (!empty($post_ids)) : ?>
            <section id="pastEvents">
                <div class="section-id" id="minule-podujatia"></div>
                <div class="container">
                    <div class="heading-container d-flex justify-content-between align-items-center flex-wrap">
                        <h1>Uplynulé podujatia</h1>
                        <div class="categories d-flex pb-md-0 pb-2 justify-content-md-end mt-md-0 mt-5 justify-content-start align-items-center gap-2 flex-nowrap">
                            <div class="category tag medium white" v-for="(year, index) in years" :key="index"
                                 :class="activeYear === year ? 'active' : ''"
                                 @click="fetchContent(year)" v-html="year"></div>
                        </div>
                    </div>
                    <div class="posts-wrapper">
                        <div class="loader" :class="loading ? 'active' : ''">
                            <lord-icon
                                    src="<?= icon_path() ?>/icon-loader-three-dots.json"
                                    trigger="loop"
                                    colors="primary:#F03834,secondary:#FFFFFF">
                            </lord-icon>
                        </div>
                        <div class="position-relative" v-html="postsContent"></div>
                    </div>
                </div>
            </section>
        <?php endif ?>
        <!--   PAST EVENTS ----- END    -->

    </div>

<?php get_footer(); ?>