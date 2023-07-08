<div class="hidden-footer">
    <?php
    /* Template Name: Contact */
    get_header();
    ?>
    <section id="contact" class="bg-blue-gradient-side-mirrored">
        <div class="section-id" id="kontakt"></div>
        <div class="container">
            <div class="row">
                <div class="form-wrapper col-md-8">
                    <h1>Kontaktný formulár</h1>
                    <div class="btns d-flex align-items-center justify-content-center justify-content-md-start flex-wrap gap-3">
                        <button @click="toggleActiveForm('contact')" class="btn btn--small btn--red" :class="activeForm === 'contact' ? 'btn--red active' : 'btn--white'">
                            <span>Opýtať sa</span>
                        </button>
                        <button @click="toggleActiveForm('playground')" class="btn btn--small" :class="activeForm === 'playground' ? 'btn--red active' : 'btn--white'">
                            <span>Nahlásiť ihrisko</span>
                        </button>
                    </div>
                    <div class="form-container" v-show="activeForm === 'contact'" id="contactForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-container movable-label required is-text" data-name="meno">
                                    <label for="meno">Meno a priezvisko</label>
                                    <input v-model="contactForm.meno" class="form-field" type="text" id="meno">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-container movable-label required is-email" data-name="email">
                                    <label for="email">Email</label>
                                    <input v-model="contactForm.email" class="form-field" type="email" id="email">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field-container movable-label required is-textarea is-text" data-name="sprava">
                                    <label for="email">Správa</label>
                                    <textarea v-model="contactForm.sprava" class="form-field" id="sprava"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" @click="sendForm()" class="ml-auto learn-more-btn mt-5">
                                    <?= __("Odoslať", "swslovakia") ?>
                                    <span class="icon">
                                        <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-container" v-show="activeForm === 'playground'" id="playgroundForm"></div>
                </div>
                <div class="contact-info-wrapper d-none d-md-flex col-md-4 justify-content-end flex-column">
                    <h3 class="mb-3">Kontakt</h3>
                    <?php if ($address = get_field("contact_address", "options")) : ?>
                        <p class="address mb-3"><?= $address ?></p>
                    <?php endif ?>
                    <div class="contact-info-container mt-2 mb-4">
                        <?php get_template_part("template_parts/other/contact-info"); ?>
                    </div>
                    <?php get_template_part("template_parts/other/social-icons", "", ['color' => 'red']); ?>
                </div>
            </div>
        </div>
        <div class="img-container">
            <img src="<?= get_field("contact_form_image") ?>" alt="Obrázok - kontakt">
            <div class="contact-info-wrapper d-md-none d-flex justify-content-end flex-column">
                <h3 class="mb-3">Kontakt</h3>
                <?php if ($address = get_field("contact_address", "options")) : ?>
                    <p class="address mb-3"><?= $address ?></p>
                <?php endif ?>
                <div class="contact-info-container mt-2 mb-4">
                    <?php get_template_part("template_parts/other/contact-info"); ?>
                </div>
                <?php get_template_part("template_parts/other/social-icons", "", ['color' => 'red']); ?>
            </div>
        </div>
    </section>
    <?php get_footer(); ?>
</div>