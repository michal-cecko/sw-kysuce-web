<table border="0" cellpadding="0" cellspacing="0" align="center"
       width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
	max-width: 560px;" class="wrapper">

    <!-- SOCIAL NETWORKS -->
    <!-- Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2 -->
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
			padding-top: 25px;" class="social-icons">
            <table
                    width="256" border="0" cellpadding="0" cellspacing="0" align="center"
                    style="border-collapse: collapse; border-spacing: 0; padding: 0;">
                <tr>

                    <?php

                    $fb = get_field("socials_facebook", "options");
                    $ig = get_field("socials_instagram", "options");

                    ?>

                    <!-- WEB -->
                    <td align="center" valign="middle"
                        style="margin: 0; padding: 0; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;">
                        <a target="_blank" href="<?= get_site_url() ?>" style="text-decoration: none;">
                            <img border="0" vspace="0" hspace="0" width="44" height="44" style="padding: 0; margin: 0; outline: none;
                                        text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block; color: #000000;"
                                 src="<?= get_template_directory_uri() ?>/assets/icons/icon-web.png">
                        </a>
                    </td>

                    <?php if ($fb) : ?>
                        <!-- FACEBOOK -->
                        <td align="center" valign="middle"
                            style="margin: 0; padding: 0; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;">
                            <a target="_blank" href="<?= $fb ?>" style="text-decoration: none;">
                                <img border="0" vspace="0" hspace="0" width="44" height="44"
                                     style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block; color: #000000;"
                                     src="<?= get_template_directory_uri() ?>/assets/icons/icon-facebook.png">
                            </a>
                        </td>
                    <?php endif ?>

                    <?php if ($ig) : ?>
                        <!-- INSTAGRAM -->
                        <td align="center" valign="middle"
                            style="margin: 0; padding: 0; padding-left: 10px; padding-right: 10px; border-collapse: collapse; border-spacing: 0;">
                            <a target="_blank" href="<?= $ig ?>"
                               style="text-decoration: none;">
                                <img border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: inline-block;
					color: #000000;" alt="I" title="Instagram" width="44" height="44"
                                     src="<?= get_template_directory_uri() ?>/assets/icons/icon-instagram.png">
                            </a>
                        </td>
                    <?php endif ?>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%;
			padding-top: 20px;
			padding-bottom: 20px;
			color: #999999;
			font-family: sans-serif;" class="footer">
            Tento email Vám bol odoslaný z webu <a href="<?= get_site_url() ?>/" target="_blank" style="text-decoration: underline; color: #999999; font-family: sans-serif; font-size: 13px; font-weight: 400; line-height: 150%;"><?= get_bloginfo('name') ?></a>.
        </td>
    </tr>

    <!-- End of WRAPPER -->
</table>