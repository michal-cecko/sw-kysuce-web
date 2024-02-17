<html xmlns="http://www.w3.org/1999/xhtml">

<?php
$data = $args['data'] ?? [];
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
<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%"
      style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;background-color: #F0F0F0; color: #000000;"
      bgcolor="#F0F0F0" text="#000000">

<!-- SECTION / BACKGROUND -->
<!-- Set message background color one again -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0"
       style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background">
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
            bgcolor="#F0F0F0">

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
                <?php if ($add_to_calendar) : ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/paragraph", ['content' => $add_to_calendar['text']]) ?>
                    <?= get_template_part_as_string("template_parts/emails/parts/buttons", ['buttons' => [$add_to_calendar]]); ?>
                <?php endif ?>

                <?= get_template_part_as_string("template_parts/emails/parts/horizontal-line") ?>
            </table>

            <?= get_template_part_as_string("template_parts/emails/parts/socials") ?>

        </td>
    </tr>
</table>

</body>
</html>