<html xmlns="http://www.w3.org/1999/xhtml">

<?php
$data = $args['data'];
$title = $args['data']['title'] ?? null;
$subtitle = $args['data']['subtitle'] ?? null;
$content = $args['data']['content'] ?? null;
$hero_image = $args['data']['hero_image'] ?? null;
$hero_image_link = $args['data']['hero_image_link'] ?? null;
$add_to_calendar = $args['data']['add_to_calendar'] ?? null;
?>

<?= get_template_part_as_string("template_parts/emails/parts/head") ?>

<!-- BODY -->
<!-- Set message background color (twice) and text color (twice) -->
<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
	background-color: #F0F0F0;
	color: #000000;" bgcolor="#F0F0F0" text="#000000">

<!-- SECTION / BACKGROUND -->
<!-- Set message background color one again -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background">
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;" bgcolor="#F0F0F0">

            <!-- IMAGE LOGO -->
            <?= get_template_part_as_string("template_parts/emails/parts/preheader", [
                'link' => get_site_url(),
                //TODO IMAGE HERE
                'image' => ""
            ]) ?>

            <!-- WRAPPER / CONTEINER -->
            <!-- Set conteiner background color -->
            <table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF" width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
	max-width: 560px;" class="container">

                <!-- HEADER -->
                <?php if ($title) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/title", [$title]) ?>
                <?php endif ?>

                <?php if ($subtitle) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/subtitle", [$subtitle]) ?>
                <?php endif ?>

                <!-- HERO IMAGE -->
                <?php if ($hero_image) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/full-width-image", ['image' => $hero_image, 'link' => $hero_image_link]) ?>
                <?php endif ?>

                <!-- CONTENT -->
                <?php if ($content) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/paragraph", [$content]) ?>
                <?php endif ?>

                <!-- ADD TO CALENDAR -->
                <?php if ( $add_to_calendar ) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/paragraph", ['content' => $add_to_calendar['text']]) ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/buttons", ['buttons' => [$add_to_calendar]]); ?>
                <?php endif ?>


                <!-- LINE -->
                <!-- Set line color -->
                <!--                <tr>
                                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
                            padding-top: 25px;" class="line"><hr
                                            color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
                                    </td>
                                </tr>-->

                <!-- LIST -->
                <!--                <tr>
                                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%;" class="list-item"><table align="center" border="0" cellspacing="0" cellpadding="0" style="width: inherit; margin: 0; padding: 0; border-collapse: collapse; border-spacing: 0;">

                                            &lt;!&ndash; LIST ITEM &ndash;&gt;
                                            <tr>

                                                &lt;!&ndash; LIST ITEM IMAGE &ndash;&gt;
                                                &lt;!&ndash; Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2 &ndash;&gt;
                                                <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                                    padding-top: 30px;
                                    padding-right: 20px;"><img
                                                        border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;
                                    color: #000000;"
                                                        src="https://raw.githubusercontent.com/konsav/email-templates/master/images/list-item.png"
                                                        alt="H" title="Highly compatible"
                                                        width="50" height="50"></td>

                                                &lt;!&ndash; LIST ITEM TEXT &ndash;&gt;
                                                &lt;!&ndash; Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height &ndash;&gt;
                                                <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                                    padding-top: 25px;
                                    color: #000000;
                                    font-family: sans-serif;" class="paragraph">
                                                    <b style="color: #333333;">Highly compatible</b><br/>
                                                    Tested on the most popular email clients for web, desktop and mobile. Checklist included.
                                                </td>

                                            </tr>

                                            &lt;!&ndash; LIST ITEM &ndash;&gt;
                                            <tr>

                                                &lt;!&ndash; LIST ITEM IMAGE &ndash;&gt;
                                                &lt;!&ndash; Image text color should be opposite to background color. Set your url, image src, alt and title. Alt text should fit the image size. Real image size should be x2 &ndash;&gt;
                                                <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                                    padding-top: 30px;
                                    padding-right: 20px;"><img
                                                        border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;
                                    color: #000000;"
                                                        src="https://raw.githubusercontent.com/konsav/email-templates/master/images/list-item.png"
                                                        alt="D" title="Designer friendly"
                                                        width="50" height="50"></td>

                                                &lt;!&ndash; LIST ITEM TEXT &ndash;&gt;
                                                &lt;!&ndash; Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height &ndash;&gt;
                                                <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                                    padding-top: 25px;
                                    color: #000000;
                                    font-family: sans-serif;" class="paragraph">
                                                    <b style="color: #333333;">Designer friendly</b><br/>
                                                    Sketch app resource file and a&nbsp;bunch of&nbsp;social media icons are&nbsp;also included in&nbsp;GitHub repository.
                                                </td>

                                            </tr>

                                        </table></td>
                                </tr>-->

                <!-- LINE -->
                <!-- Set line color -->
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
			padding-top: 25px;" class="line">
                        <hr
                                color="#E0E0E0" align="center" width="100%" size="1" noshade
                                style="margin: 0; padding: 0;"/>
                    </td>
                </tr>

                <!-- PARAGRAPH -->
                <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
			padding-top: 20px;
			padding-bottom: 25px;
			color: #000000;
			font-family: sans-serif;" class="paragraph">
                        Máte nejaké otázky? <a href="mailto:info@trimbarbers.sk" target="_blank"
                                               style="color: #127DB3; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 160%;">info@trimbarbers.sk</a>
                    </td>
                </tr>

                <!-- End of WRAPPER -->
            </table>
        </td>
    </tr>
</table>

</body>
</html>