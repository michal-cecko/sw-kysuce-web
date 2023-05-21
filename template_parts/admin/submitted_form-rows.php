<?php
$form = $args['form'] ?? false;
?>
<div class="submitted-forms">
    <?php if (!empty($args['rows'])) : $rows = $args['rows']; ?>
        <?php foreach ($rows as $row) : ?>
            <div class="row">
                <div class="fields">
                    <?php foreach ($row as $fieldsName => $fieldValue) : ?>
                    <div class="field">
                        <div class="name"><?= $fieldsName ?></div>
                        <?php if (str_starts_with($fieldValue, "http")) : ?>
                            <a href="<?= $fieldValue ?>" target="_blank" class="value">Otvoriť súbor</a>
                        <?php else : ?>
                            <div class="value"><?= $fieldValue ?></div>
                        <?php endif ?>

                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="actions">
                    <span class="dashicons dashicons-trash remove-submitted-row" data-id="<?= $row['id'] ?>" data-form_id="<?= $form->ID ?>"></span>
                </div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <div class="empty">Zatiaľ nebol odoslaný žiadny formulár.</div>
    <?php endif ?>
</div>