<?php
$buttons = $args['buttons'] ?? [];
if (!is_array($buttons))
    $buttons = [$buttons];
?>
<tr>
    <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; padding-top: 25px; text-align: center;
			padding-bottom: 5px;" class="button">
        <?php foreach ($buttons as $button) : ?>
            <a href="<?= $button['url'] ?>" target="_blank" style="text-decoration: none; float: left; margin-right: 10px; margin-bottom: 10px">
                <table border="0" cellpadding="0" cellspacing="0" align="center"
                       style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;">
                    <tr>
                        <td align="center" valign="middle"
                            style="padding: 12px 24px; margin: 0; text-decoration: none; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
                            bgcolor="<?= $button['color'] ?? "#42a1f5" ?>">
                            <a target="_blank"
                               style="text-decoration: none;color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;"
                               href="<?= $button['url'] ?>">
                                <?= $button['name'] ?>
                            </a>
                        </td>
                    </tr>
                </table>
            </a>
        <?php endforeach ?>
    </td>
</tr>