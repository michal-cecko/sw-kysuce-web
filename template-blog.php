<?php
/* Template Name: Blog */
get_header();


$stickyPosts = get_option('sticky_posts');

$args = [
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'post_type' => 'post',
    'post__in' => $stickyPosts
];

$highlights = new WP_Query($args);

$categories = get_categories(['hide_empty' => true, 'exclude' => [1]])

?>
    <div id="blogdata" data-post_type="post"></div>

    <div id="blog">
        <!--   INTRO SECTION TEXT ----- START    -->

        <section id="blogIntro" class="bg-blue-gradient-side-mirrored">
            <div class="section-id" id="intro"></div>
            <div class="container">
                <span class="bg-text">blog</span>
                <div class="text-container">
                    <h1 class="intro-heading">
                        <?= __("Nenechajte si ujsť", "swslovakia") ?>
                        <?= __("naše", "swslovakia") ?>
                        <span class="labelled-text red big">
                            <span><?= __("novinky", "swslovakia") ?></span>
                        </span>
                    </h1>
                </div>
            </div>
        </section>

        <!--   INTRO SECTION TEXT ----- END    -->


        <!--   HIGHLIGHTED POSTS ----- START    -->

        <section id="blogHighlights">
            <div class="section-id" id="pripnute-clanky"></div>
            <?php if ($highlights->have_posts()) : ?>
                <div class="slick-highlights">
                    <?php while ($highlights->have_posts()) :
                        $highlights->the_post();
                        $category = getPostCategory(get_the_ID()) ?>
                        <div class="slick-slide">
                            <div class="highlight-post">
                                <div class="img-container">
                                    <?php the_post_thumbnail(); ?>
                                </div>
                                <div class="text-container">
                                    <div class="dot-divided-info red-dot">
                                        <div class="info-container">
                                            <?php if ($category) : ?>
                                                <div class="tag black small"><?= $category->name ?></div>
                                            <?php endif ?>
                                            <div class="dot-divided-info">
                                                <span><?= get_the_date("d. M Y") ?></span>
                                                <span><?= reading_time(get_the_ID()) ?> min</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="title"><?= get_the_title() ?></div>
                                    <a href="<?= get_the_permalink() ?>" class="learn-more-btn mt-3">
                                        <?= __("Prečítať", "swslovakia") ?>
                                        <span class="icon">
                                                <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                                            </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile ?>
                </div>
                <div class="slick-highlights-arrows">
                    <div class="slick-arrow prev">
                        <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                    </div>
                    <div class="slick-arrow next">
                        <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                    </div>
                </div>
            <?php endif ?>
        </section>

        <!--   HIGHLIGHTED POSTS ----- END    -->

        <!--   POSTS LIST ----- START    -->

        <section id="latestNews">
            <div class="section-id" id="posledne-novinky"></div>
            <div class="container">
                <div class="heading-container row mb-4">
                    <div class="col-lg-4">
                        <h2 class="heading">Posledné novinky</h2>
                    </div>
                    <div class="col-lg-8">
                        <div class="categories d-flex justify-content-md-end mt-md-0 mt-5 justify-content-start align-items-center gap-2 flex-nowrap">
                            <div class="category tag white" :class="activeCategory === -1 ? 'active' : ''"
                                 @click="fetchContent(-1)">Všetko
                            </div>
                            <?php foreach ($categories as $category) : ?>
                                <div class="category tag white"
                                     :class="activeCategory === <?= $category->term_id ?> ? 'active' : ''"
                                     @click="fetchContent(<?= $category->term_id ?>)"><?= $category->name ?></div>
                            <?php endforeach ?>
                        </div>
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