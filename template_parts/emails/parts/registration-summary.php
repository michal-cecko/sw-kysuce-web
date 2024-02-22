<?php
$data = $args['data'] ?? [];
$photoKey = 'fotka-atleta';
$image = $data[$photoKey] ?? null;
?>
<table>
    <tr>
        <?php if ($image) : ?>
            <td style="width: 50%" valign="top">
                <img border="0" vspace="0" hspace="0"
                     src="<?= $image ?>"
                     alt="Fotka atléta"
                     style="width: 90%; vertical-align: top; max-width: 270px; color: #000000; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/>
            </td>
        <?php endif ?>
        <td width="<?= $image ? "50%" : "100%" ?>" valign="top">
            <?php foreach ($data as $name => $value) : if ($name === $photoKey) continue; ?>
                <?= $name ?>: <br><b>
                    <?php if (is_array($value)) : ?>
                        <?= implode(", ", $value) ?>
                    <?php else : ?>
                        <?= $value ?>
                    <?php endif ?>
                </b><br><br>
            <?php endforeach ?>
        </td>
    </tr>
</table>