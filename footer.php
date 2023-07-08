<footer>
    <div class="container">
        <div class="row">
            <div class="logo-container col-lg-3">
                <?= svgIcon(icon_path(false) . "logo.svg", ['class' => ['logo']]) ?>
                <?php get_template_part("template_parts/other/contact-info"); ?>
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
                    Nezabudni nám hodiť follow, aby ti nič neuniklo!
                </p>
                <?php get_template_part("template_parts/other/social-icons", "", ['color' => 'black']); ?>
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