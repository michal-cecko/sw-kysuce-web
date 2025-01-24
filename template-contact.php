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
<!--                        <button @click="toggleActiveForm('playground')" class="btn btn&#45;&#45;small" :class="activeForm === 'playground' ? 'btn&#45;&#45;red active' : 'btn&#45;&#45;white'">
                            <span>Nahlásiť ihrisko</span>
                        </button>-->
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
                    <div class="form-container" v-show="activeForm === 'playground'" id="playgroundForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-field-container movable-label is-text" data-name="meno">
                                    <label for="meno">Meno a priezvisko</label>
                                    <input v-model="playgroundForm.meno" class="form-field" type="text" id="meno">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-container movable-label required is-email" data-name="email">
                                    <label for="email">Email</label>
                                    <input v-model="playgroundForm.email" class="form-field" type="email" id="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-container movable-label is-text" data-name="nazov">
                                    <label for="nazov">Názov ihriska</label>
                                    <input v-model="playgroundForm.nazov" class="form-field" type="text" id="nazov">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field-container movable-label required is-textarea is-text" data-name="poloha">
                                    <label for="poloha">Poloha ihriska</label>
                                    <textarea v-model="playgroundForm.poloha" class="form-field" id="poloha"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field-container is-file-input required" onclick="document.getElementById('fotky').click();" data-allowed_types="JPG,JPEG,PNG" data-name="fotky">
                                    <input type="file" class="form-field" id="fotky" multiple>
                                    <div class="drop-here-text d-none"></div>
                                    <div class="drag-drop-text">
                                        <div class="upload-icon-text">
                                            <h6>
                                                <span class="icon-container">
                                                    <?= svgIcon(icon_path(false) . "icon-upload.svg", ['class' => ['icon']]) ?>
                                                </span>
                                                <span class="choose-file-btn text-center">Fotky ihriska</span>
                                            </h6>
                                            <p>
                                                Povolené typy
                                                <span>(.JPG, .JPEG, .PNG)</span>
                                            </p>
                                        </div>
                                        <div class="text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="button" @click="sendForm()" class="ml-auto learn-more-btn mt-5">
                                    <?= __("Nahlásiť ihrisko", "swslovakia") ?>
                                    <span class="icon">
                                        <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
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