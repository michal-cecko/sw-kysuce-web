<?php $form = $args['form'] ?? false; ?>

<?php if ($form) : ?>
    <div class="form-container">
        <?php $fields = get_field("form_fields", $form->ID) ?>
        <?php foreach ($fields as $field) : $id = sanitize_title($field['name']);
            if($field['is_hidden'] ?? false) continue; ?>

            <?php if ($field['acf_fc_layout'] === "text" || $field['acf_fc_layout'] === "email") : ?>
                <div class="form-field-container movable-label <?= ($field['is_textarea'] ?? false) ? "is-textarea" : "" ?> <?= required($field) ?> <?= $field['acf_fc_layout'] === "email" ? 'is-email' : 'is-text' ?>"
                     data-name="<?= $field['name'] ?>">
                    <?= infoTooltip($field) ?>
                    <label for="<?= $id ?>"><?= $field['name'] ?> <?= requiredLabel($field) ?></label>
                    <?php if ($field['is_textarea'] ?? false) : ?>
                        <textarea v-model="fields['<?= $id ?>']" class="form-field" name="<?= $id ?>"
                                  id="<?= $id ?>"><?= $field['default_value'] ?? "" ?></textarea>
                    <?php else : ?>
                        <input v-model="fields['<?= $id ?>']" class="form-field"
                               type="<?= $field['acf_fc_layout'] === "email" ? 'email' : 'text' ?>" name="<?= $id ?>"
                               id="<?= $id ?>" data-default="<?= $field['default_value'] ?? "" ?>">
                    <?php endif ?>
                </div>

            <?php elseif ($field['acf_fc_layout'] === "options") : ?>

                <?php if ($field['select_or_checkbox']) : ?>

                    <div class="form-field-container movable-label custom-select <?= required($field) ?> <?= $field['multiple'] ? 'multiple' : '' ?>"
                         data-name="<?= $field['name'] ?>">
                        <?= infoTooltip($field) ?>
                        <label for="<?= $id ?>">
                            <?= $field['name'] ?>
                            <?= requiredLabel($field) ?>
                            <?= svgIcon(icon_path(false) . "icon-arrow_bottom.svg", ['class' => ['arrow']]) ?>
                        </label>
                        <div class="selected-values"></div>
                        <div class="options">
                            <?php $defaults = [];
                            if (!empty($field['options'])) : ?>
                                <?php foreach ($field['options'] as $option) :
                                    if ($option['is_default'] ?? false) $defaults[] = $option['option'];
                                    ?>
                                    <div class="option <?= $option["is_default"] ? "selected" : "" ?>"
                                         data-value="<?= $option["option"] ?>"><?= $option["option"] ?></div>
                                <?php endforeach ?>
                            <?php else : ?>
                                <div class="empty">
                                    Nieje žiadna možnosť na výber.
                                </div>
                            <?php endif ?>
                        </div>
                        <input v-model="fields['<?= $id ?>']" type="hidden" name="<?= $id ?>" id="<?= $id ?>" @change="updateValue('<?= $id ?>', $event?.target?.value)"
                               class="form-field" data-default="<?= implode("###", $defaults) ?>">
                    </div>

                <?php else : ?>

                    <div class="form-field-container checkboxes-container <?= required($field) ?> <?= $field['multiple'] ? 'multiple' : '' ?>"
                         data-name="<?= $field['name'] ?>" data-id="<?= $id ?>">
                        <?= infoTooltip($field) ?>
                        <label><?= $field['name'] ?> <?= requiredLabel($field) ?></label>
                        <div class="checkboxes">
                            <?php if (!empty($field['options'])) : ?>
                                <?php $i = 0;
                                foreach ($field['options'] as $option) : ?>
                                    <div class="checkbox-container">
                                        <input type="<?= $field['multiple'] ? 'checkbox' : "radio" ?>" class="form-field" v-model="fields['<?= $id ?>']"
                                               name="<?= $id ?><?= $field['multiple'] ? '[]' : "" ?>" id="<?= $id . "&" . $i ?>"
                                               value="<?= $option['option'] ?>" <?= $option['is_default'] ? "checked" : "" ?>>
                                        <label for="<?= $id . "&" . $i ?>" class="checkbox"></label>
                                        <p><?= $option['option'] ?></p>
                                    </div>
                                    <?php $i++; endforeach ?>
                            <?php else : ?>
                                <div class="empty">
                                    Nieje žiadna možnosť na výber.
                                </div>
                            <?php endif ?>
                        </div>
                    </div>

                <?php endif ?>

            <?php elseif ($field['acf_fc_layout'] === "file") :
                $allowed = implode(", ", explode(",", str_replace(" ", "", strtoupper($field['allowed_file_types'])))) ?>
                <div class="position-relative">
                    <?= infoTooltip($field) ?>
                    <div class="form-field-container is-file-input <?= required($field) ?>" style="z-index: 5"
                         onclick="document.getElementById('<?= $id ?>').click();" data-allowed_types="<?= $allowed ?>"
                         data-name="<?= $field['name'] ?>">
                        <input type="file" class="form-field" name="<?= $id ?>" id="<?= $id ?>">
                        <div class="drop-here-text d-none"></div>
                        <div class="drag-drop-text">
                            <div class="upload-icon-text">
                                <h6>
                                <span class="icon-container">
                                    <?= svgIcon(icon_path(false) . "icon-upload.svg", ['class' => ['icon']]) ?>
                                </span>
                                    <span class="choose-file-btn text-center"><?= $field['name'] ?></span>
                                </h6>
                                <?php if (!empty($field['allowed_file_types'])) : ?>
                                    <p>
                                        Povolené typy
                                        <span>(<?= $allowed ?>)</span>
                                    </p>
                                <?php endif ?>
                            </div>
                            <div class="text"></div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>


    <button type="button" @click="send()" class="ml-auto learn-more-btn btn--loader mt-5 preloader--display"
            :class="sending ? 'loading' : ''">
        {{sending ? 'Odosielam' : 'Odoslať'}}
        <span class="icon">
            <lord-icon src="<?= icon_path() ?>/icon-loader-two-dots.json" trigger="loop" class="loader"
                       colors="primary:#FFFFFF,secondary:#FFFFFF"></lord-icon>
            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
        </span>
    </button>
<?php endif ?>


<?php

function required($field)
{
    return $field['is_required'] ? 'required' : '';
}

function requiredLabel($field)
{
    return $field['is_required'] ? "" : "<span>(" . __("Volitelné", "swslovakia") . ")</span>";
}

function infoTooltip($field) {
    ob_start();
    if(!empty($field['info'])) :
?>
    <div class="tooltip-container show-on-left" style="z-index: 6">
        <div class="tooltip">
            <div class="icon">
                <?= svgIcon(icon_path(false) . "icon-info.svg") ?>
            </div>
            <div class="info">
                <?= $field['info'] ?>
            </div>
        </div>
    </div>
    <?php endif; return ob_get_clean();
}
?>