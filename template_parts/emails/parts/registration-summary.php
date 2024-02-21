<?php
    $data = $args['data'] ?? [];
    $photoKey = 'Fotka atléta';
    $image = $data[$photoKey] ?? null;
?>
<table>
    <tr>
        <?php if ($image) : ?>
        <td style="width: 50%">
            <img border="0" vspace="0" hspace="0" width="50%"
                 src="<?= $image ?>"
                 alt="Fotka atléta"
                 style="width: 100%; max-width: 150px; color: #000000; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/>
        </td>
        <?php endif ?>
        <td width="<?= $image ? "50%" : "100%" ?>">
            <?php foreach ($data as $name => $value) : ?>
                <?= $name ?>: <b><?= $value ?></b>
            <?php endforeach ?>
        </td>
    </tr>
</table>