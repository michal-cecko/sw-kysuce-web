<div class="submitted-forms">
    <?php if (false) : ?>
        <?php if (!empty($args['rows'])) : $rows = $args['rows']; ?>
            <?php foreach ($rows as $row) : var_dump($row); ?>
                <div class="row">
                    <div class="fields">
                        <?php /*foreach ($fields as $field) : var_dump($field); */?><!--
                        <div class="field">
                            <div class="name">Meno a priezvisko</div>
                            <div class="value">Fero Trnka</div>
                        </div>
                    --><?php /*endforeach;*/ ?>
                    </div>
                    <div class="actions">
                        <span class="dashicons dashicons-trash remove-submitted-row" data-id="<?= $row ?>"></span>
                    </div>
                </div>
            <?php endforeach ?>
        <?php else : ?>
            <div class="empty">Zatiaľ nebol odoslaný žiadny formulár.</div>
        <?php endif ?>
    <?php endif ?>
</div>