<?php
/* Template Name: Playgrounds */
get_header();

$args = [
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_type' => 'playground',
];

$playgrounds = new WP_Query($args);

?>

    <div id="blogdata" data-post_type="playground"></div>

    <div id="blog">
        <!--   INTRO SECTION TEXT ----- START    -->

        <section id="eventsIntro" class="bg-blue-gradient-side-mirrored">
            <div class="section-id" id="intro"></div>
            <div class="container">
                <span class="bg-text">workouts</span>
                <div class="text-container">
                    <h1 class="intro-heading">
                        <?= __("Zoznam workoutových ihrísk", "swslovakia") ?>
                    </h1>
                </div>
            </div>
        </section>

        <!--   INTRO SECTION TEXT ----- END    -->


        <!--   MAP ----- START    -->
        <section id="mapSection">
            <div id="map"></div>
        </section>
        <!--   MAP ----- END    -->


        <!--   POSTS LIST ----- START    -->

        <section id="latestPlaygrounds">
            <div class="section-id" id="posledne-novinky"></div>
            <div class="container">
                <div class="heading-container row mb-4">
                    <div class="col-lg-6">
                        <h2 class="heading">Zoznam známych ihrísk</h2>
                    </div>
                    <div class="col-lg-6">
                        <p>Lorem ipsum dolor sir amet...</p>
                        <a href="<?= get_site_url() ?>/contact" class="learn-more-btn">
                            <?= __("Nahlásiť ihrisko", "swslovakia") ?>
                            <span class="icon">
                            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                        </span>
                        </a>
                    </div>
                </div>

                <div class="posts-wrapper" v-html="postsContent"></div>

                <Pagination
                        v-if="pagination && pagination.total_pages > 1"
                        :current-page="currentPage"
                        :total-pages="pagination.total_pages"
                        :display-pages="5"
                        @page-changed="changePage"
                ></Pagination>
            </div>
        </section>

        <!--   POSTS LIST ----- END    -->

    </div>

<?php get_footer(); ?>