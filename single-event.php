<?php
/* Template Name: Single / Event */
get_header();

$formLink = get_field("event_register_form");
$externalLink = get_field("event_register_link");
$hasLocalForm = !empty($formLink);

?>

<div id="event">
    <div id="eventData" data-id="<?= get_the_ID() ?>"></div>
    <!--   INTRO ----- START    -->
    <section id="eventIntro" class="bg-blue-gradient-side-mirrored">
        <div class="container">
            <div class="bg-text">Registrácia</div>
            <div class="row">
                <div class="col-md-6 card-column">
                    <?php get_template_part("template_parts/events/event-card", "", [
                        'event' => get_post(),
                        'show_registration_link' => false,
                        'show_description' => true,
                    ]) ?>
                </div>
                <div class="col-md-6 register-column <?= $hasLocalForm ? 'has-form' : 'has-external-link' ?>">
                    <h3>Registračný formulár</h3>
                    <?php if ($hasLocalForm) : ?>

                        <p>Pre registráciu na toto podujatie prosím vyplňte registračný formulár nižšie.</p>

                        <?php
                        get_template_part("template_parts/events/register-form", "", [
                            'form' => get_field("event_register_form"),
                        ]);
                        ?>

                    <?php else : ?>

                        <p>Pre registráciu na toto podujatie prosím kliknite na link pomocou tlačidla nižšie. Registrácie sú na externom portáli, za ktorý sa zaručujeme.</p>
                        <a href="<?= $externalLink ?>" class="btn btn--medium btn--red">
                        <span>
                            Registrácia
                            <?= svgIcon(icon_path(false) . "/icon-arrow_right.svg", ['class' => ['ml-1']]) ?>
                        </span>
                        </a>

                    <?php endif ?>

                </div>
            </div>
        </div>
    </section>
    <!--   INTRO ----- END    -->

</div>

<?php get_footer(); ?>