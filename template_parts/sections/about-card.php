<section id="about-card">
    <div class="custom-bg"></div>
    <div class="container">
        <div class="row">
            <?php $img = get_field("section-about_img_1", "options") ?>
            <?php if ($img) : ?>
                <div class="col-md-5">
                    <div class="img-wrapper">
                        <div class="img-container">
                            <img src="<?= $img ?>" alt="">
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="<?= $img ? "col-md-7" : "col-12" ?>">
                <div class="text-container">
                    <h4 class="heading on-black mb-5"><?= get_field("section-about_heading", "options") ?></h4>
                    <?php if ($text = get_field("section-about_text", "options")) : ?>
                        <p class="secondary-text on-black mb-4"><?= $text ?></p>
                    <?php endif ?>
                    <a href="<?= get_site_url() ?>/o-nas" class="learn-more-btn">
                        <?= __("Spoznaj nás bližšie", "swslovakia") ?>
                        <span class="icon">
                            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <?php if ($img = get_field("section-about_img_2", "options")) : ?>
            <div class="abs-img-1">
                <img src="<?= $img ?>" alt="">
            </div>
        <?php endif ?>

        <?php if ($img = get_field("section-about_img_3", "options")) : ?>
            <div class="abs-img-2">
                <img src="<?= $img ?>" alt="" class="abs-img-2">
            </div>
        <?php endif ?>
    </div>
</section>