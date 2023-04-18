<?php
/* Template Name: O nás */
get_header();
?>


    <!--   INTRO SECTION TEXT ----- START    -->

    <section id="aboutIntro">
        <div class="section-id" id="intro"></div>
        <div class="container">
            <span class="bg-text">team</span>
            <div class="text-container">
                <h1>
                    <?= __("Sme", "swslovakia") ?>
                    <span class="labelled-text red big">
                    <span><?= __("experti", "swslovakia") ?></span>
                </span>
                    <?= __(", no", "swslovakia") ?>
                    <br>
                    <?= __("hlavne tímoví hráči", "swslovakia") ?>
                </h1>
                <a href="#" class="scroll-button">
                    <?= svgIcon(icon_path(false) . "icon-arrow_bottom.svg") ?>
                </a>
            </div>
        </div>
    </section>

    <!--   INTRO SECTION TEXT ----- END    -->



    <!--   INTRO SECTION IMAGE ----- START    -->

    <section id="aboutIntroImage">
        <div class="img-container">
            <img src="<?= get_template_directory_uri() ?>/assets/images/about-intro.jpg" alt="O nás - intro">
        </div>
    </section>

    <!--   INTRO SECTION IMAGE ----- END    -->


    <section id="about">
        <div class="section-id" id="nas-pribeh"></div>
    </section>

<?php get_footer(); ?>