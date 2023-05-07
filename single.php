<?php
/* Template Name: Single / Post */
get_header();

$category = getPostCategory(get_the_ID());
?>


<!--   INTRO ----- START    -->
<section id="articleIntro" class="bg-blue-gradient-side-mirrored">
    <div class="container">
        <div class="bg-text">článok</div>
        <div class="row intro-text-container">
            <div class="col-md-7 d-flex justify-content-md-center flex-column justify-content-end">
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
                <h1 class="title mt-4 mb-5"><?= get_the_title() ?></h1>
                <a href="#obsah-clanku" class="learn-more-btn down bigger mb-md-0 mb-5">
                    <span class="icon">
                        <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg") ?>
                    </span>
                </a>
            </div>
            <?php $permalink = get_the_permalink() ?>
            <div class="col-md-5 ps-md-5 ps-2 d-flex justify-content-md-center justify-content-start flex-column">
                <div class="d-flex justify-content-md-end justify-content-center mt-md-0 mt-5 align-items-center gap-3">
                    <div class="share-icon js-copy_to_clipboard" data-copy="<?= $permalink ?>">
                        <?= svgIcon(icon_path(false) . "/icon-link.svg") ?>
                    </div>
                    <a href="<?= getSharerLink("facebook", $permalink) ?>" class="share-icon">
                        <?= svgIcon(icon_path(false) . "/icon-facebook.svg") ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--   INTRO ----- END    -->


<?php if (has_post_thumbnail()) : ?>
<section id="thumbSection">
    <a data-fslightbox="thumb" href="<?= get_the_post_thumbnail_url() ?>">
        <img src="<?= get_the_post_thumbnail_url() ?>" alt="Thumbnail obrázok">
    </a>
</section>
<?php endif ?>


<!--   CONTENT ----- START    -->
<section id="blogContent">
    <div class="section-id" id="obsah-clanku"></div>
    <div class="article-container">
        <div class="content-wrapper">
            <div class="content-container">
                <?php the_content(); ?>
            </div>
            <?php get_template_part("template_parts/author_card", ['author' => get_the_author_meta("ID")]) ?>
        </div>
    </div>
</section>
<!--   CONTENT ----- END    -->

<?php get_footer(); ?>