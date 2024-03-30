<?php
$data = $args['data'] ?? [];
$photoKey = 'fotka-atleta';
$image = $data[$photoKey] ?? null;
$fieldNames = $args["fieldNames"] ?? [];
?>
<?php if ($image) : ?>
    <div style="margin-bottom: 20px; display: block">

        <img border="0" vspace="0" hspace="0"
             src="<?= $image ?>"
             alt="Fotka atléta"
             style="width: 90%; vertical-align: top; max-width: 400px; color: #000000; font-size: 13px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;"/>
    </div>

<?php endif ?>
<div>
    <?php foreach ($fieldNames as $key => $name) :
        $value = $data[$key] ?? null;
        if ($key === $photoKey || empty($value)) continue;
        ?>
        <?= $name ?>: <br><b>
        <?php if (is_array($value)) : ?>
            <?= implode(", ", $value) ?>
        <?php else : ?>
            <?= $value ?>
        <?php endif ?>
    </b><br><br>
    <?php endforeach ?>
</div>
