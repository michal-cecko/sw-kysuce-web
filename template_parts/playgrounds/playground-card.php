<?php if (!empty($playground = $args['playground'])) : ?>

    <?php
    $classes = $args['classes'] ?? [];
    $images = get_field("pg-images");
    $thumbnail = $images[0] ?? false;
    $location = get_field("pg-location");
    $address = get_field("pg-address");
    $title = get_the_title();
    $id = sanitize_title($title);
    ?>

    <div class="card playground-card size-small is-column d-flex flex-column <?= implode(" ", $classes) ?>">
        <a href="<?= $thumbnail ?>" data-fslightbox="<?= $id ?>_imgs" class="img-container">
            <img src="<?= $thumbnail ?>" alt="Obrázok ihriska - <?= $title ?>">
        </a>
        <div class="text-container">
            <h3 class="title"><?= $title ?></h3>
            <?php if (!empty($address)) : ?>
                <p class="text"><?= $address ?></p>
            <?php endif ?>
        </div>
        <?php if (sizeof($images) > 1) : ?>
            <?php for ($i = 1; $i < sizeof($images); $i++) : ?>
                <a class="d-none" data-fslightbox="<?= $id ?>_imgs" href="<?= $images[$i] ?>"></a>
            <?php endfor ?>
        <?php endif ?>
    </div>
<?php endif ?>