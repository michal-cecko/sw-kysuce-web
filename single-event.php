<?php
/* Template Name: Single / Event */
get_header();

$form = get_field("event_register_form");
$externalLink = get_field("event_register_link");
$hasLocalForm = !empty($form);

?>

    <div id="eventData" data-id="<?= get_the_ID() ?>" data-form_id="<?= $form->ID ?>"></div>
    <!--   INTRO ----- START    -->
    <section id="eventIntro" class="bg-blue-gradient-side-mirrored">
        <div class="container">
            <div class="bg-text">compet</div>
            <div class="row intro-text-container">
                <div class="col-md-7 d-flex justify-content-md-center flex-column justify-content-end">
                    <div class="dot-divided-info red-dot">
                        <div class="info-container">
                            <div class="tag black small"><?= __("Súťaž", "swslovakia") ?></div>
                            <div class="dot-divided-info">
                                <span><?= getEventDate(get_the_ID()) ?></span>
                                <span><?= get_field("event_place", get_the_ID()) ?></span>
                            </div>
                        </div>
                    </div>
                    <h1 class="title mt-4 mb-md-5 mb-3"><?= get_the_title() ?></h1>
                    <a href="#registracia" class="learn-more-btn down bigger mb-0">
                        <?= __("Registrácia", "swslovakia") ?>
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


<?php if (has_post_thumbnail()) : $objectPosition = get_field("event_thumb_object_position") ?? "center"; ?>
    <section id="eventThumbSection">
        <a data-fslightbox="thumb" href="<?= get_the_post_thumbnail_url() ?>">
            <img src="<?= get_the_post_thumbnail_url() ?>" style="object-position: <?= $objectPosition ?>" alt="Thumbnail obrázok">
        </a>
    </section>
<?php endif ?>


    <!--   CONTENT ----- START    -->
    <section id="eventContent" class="bg-blue-gradient">
        <div class="section-id" id="propozicie"></div>
        <div class="event-container">
            <div class="content-wrapper">
                <div class="content-container gutenberg-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <?php
            $eventStart = get_field("event_register_start");
            $shownTimer = !empty($eventStart) && strtotime($eventStart) > time();
            if($shownTimer && get_current_user_id()) {
                $shownTimer = false;
            }
            ?>
            <div class="register-container d-flex flex-column justify-content-center align-items-center">

                <div class="section-id" id="registracia" style="margin-top: -40rem"></div>

                <?php if ($shownTimer) : ?>

                    <h3 class="mb-md-5 mb-3">Registrácie budú spustené o</h3>
                    <div id="flipdown" class="flipdown" data-start="<?= $eventStart ?>"></div>

                <?php else : ?>

                    <h3 class="mb-5">
                        <span class="labelled-text red big">
                            <span><?= __("Registrácia", "swslovakia") ?></span>
                        </span>
                    </h3>

                    <?php if ($hasLocalForm) : ?>

                        <div id="registerForm">
                            <?php get_template_part("template_parts/events/register-form", "", [
                                'form' => get_post($form),
                            ]); ?>
                        </div>

                    <?php else : ?>

                        <p>Pre registráciu na toto podujatie prosím kliknite na tlačidlo nižšie. Registrácie sú
                            na
                            externom
                            portáli, za ktorý sa zaručujeme.</p>
                        <a href="<?= $externalLink ?>" class="btn btn--medium btn--red">
                                <span>
                                    Registrácia
                                    <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg", ['class' => ['ml-1']]) ?>
                                </span>
                        </a>
                    <?php endif ?>

                <?php endif ?>

            </div>
    </section>
    <!--   CONTENT ----- END    -->

<?php get_footer(); ?>