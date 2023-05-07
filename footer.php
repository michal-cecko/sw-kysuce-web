<footer>
    <div class="container">
        <div class="row">
            <div class="logo-container col-lg-3">
                <?= svgIcon(icon_path(false) . "logo.svg", ['class' => ['logo']]) ?>
                <?php if ($phone = get_field("contact_phone", "options")) : ?>
                    <div class="contact-info phone">
                        <?php
                        echo svgIcon(icon_path(false) . "icon-phone.svg");
                        echo $phone;
                        ?>
                    </div>
                <?php endif ?>
                <?php if ($email = get_field("contact_email", "options")) : ?>
                    <div class="contact-info email">
                        <?php
                        echo svgIcon(icon_path(false) . "icon-email.svg");
                        echo $email;
                        ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="links-container col-lg-2">
                <ul>
                    <?php printMenu("footer-links"); ?>
                </ul>
            </div>
            <div class="newsletter-container col-lg-4">
                <h4 class="heading">Newsletter</h4>
                <p class="secondary-text">
                    Zaregistrujte sa do nášho newsletteru, aby ste dostali e-mail pri každom uverejnení nového článku.
                </p>
                <!--  TODO: INPUT NA NEWSLETTER  -->
            </div>
            <div class="socials-container col-lg-3">
                <h4 class="heading">Sleduj nás</h4>
                <p class="secondary-text">
                    Lorem ipsum dolor sit amet consectetur.
                </p>
                <div class="socials d-flex align-items-center flex-wrap gap-3">
                    <?php if ($fb = get_field("socials_facebook", "options")) : ?>
                        <a href="<?= $fb ?>" class="social facebook">
                            <?= svgIcon(icon_path(false) . "/icon-facebook.svg") ?>
                        </a>
                    <?php endif ?>
                    <?php if ($ig = get_field("socials_instagram", "options")) : ?>
                        <a href="<?= $ig ?>" class="social instagram">
                            <?= svgIcon(icon_path(false) . "/icon-instagram.svg") ?>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.0.9/index.min.js"
        integrity="sha512-03Ucfdj4I8Afv+9P/c9zkF4sBBGlf68zzr/MV+ClrqVCBXWAsTEjIoGCMqxhUxv1DGivK7Bm1IQd8iC4v7X2bw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>