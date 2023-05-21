<?php $form = $args['form'] ?? false; ?>

<?php if ($form) : ?>
    <div class="form-container">
        <?php $fields = get_field("form_fields", $form->ID) ?>
        <?php foreach ($fields as $field) : $id = sanitize_title($field['name']) ?>
            <?php if ($field['acf_fc_layout'] === "text" || $field['acf_fc_layout'] === "email") : ?>
                <div class="form-field-container movable-label <?= ($field['is_textarea'] ?? false) ? "is-textarea" : ""  ?> <?= required($field) ?> <?= $field['acf_fc_layout'] === "email" ? 'is-email' : 'is-text' ?>" data-name="<?= $field['name'] ?>">
                    <label for="<?= $id ?>"><?= $field['name'] ?></label>
                    <?php if ($field['is_textarea'] ?? false) : ?>
                        <textarea v-model="fields['<?= $id ?>']" class="form-field" id="<?= $id ?>"></textarea>
                    <?php else : ?>
                        <input v-model="fields['<?= $id ?>']" class="form-field" type="<?= $field['acf_fc_layout'] === "email" ? 'email' : 'text' ?>" id="<?= $id ?>">
                    <?php endif ?>
                </div>
            <?php elseif ($field['acf_fc_layout'] === "options") : ?>

                <?php if ($field['select_or_checkbox']) : ?>

                    <div class="form-field-container movable-label custom-select <?= required($field) ?> <?= $field['multiple'] ? 'multiple' : '' ?>" data-name="<?= $field['name'] ?>">
                        <input v-model="fields['<?= $id ?>']" type="hidden" id="<?= $id ?>" class="form-field">
                        <label for="<?= $id ?>">
                            <?= $field['name'] ?>
                            <?= svgIcon(icon_path(false) . "icon-arrow_bottom.svg", ['class' => ['arrow']]) ?>
                        </label>
                        <div class="selected-values"></div>
                        <div class="options">
                            <?php if (!empty($field['options'])) : ?>
                                <?php foreach ($field['options'] as $option) : $option = $option["option"]; ?>
                                    <div class="option" data-value="<?= $option ?>"><?= $option ?></div>
                                <?php endforeach ?>
                            <?php else : ?>
                                <div class="empty">
                                    Nieje žiadna možnosť na výber.
                                </div>
                            <?php endif ?>
                        </div>
                    </div>

                <?php else : ?>

                    <div class="form-field-container checkboxes-container <?= required($field) ?> <?= $field['multiple'] ? 'multiple' : '' ?>" data-name="<?= $field['name'] ?>">
                        <label><?= $field['name'] ?></label>
                        <div class="checkboxes">
                            <?php if (!empty($field['options'])) : ?>
                                <?php $i = 0; foreach ($field['options'] as $option) : $option = $option["option"]; ?>
                                <div class="checkbox-container">
                                    <input type="checkbox" class="form-field" name="<?= $id ?>[]" id="<?= $id . "&" . $i ?>" value="<?= $option ?>">
                                    <label for="<?= $id . "&" . $i ?>" class="checkbox"></label>
                                    <p><?= $option ?></p>
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
                <div class="form-field-container is-file-input <?= required($field) ?>" onclick="document.getElementById('<?= $id ?>').click();" data-allowed_types="<?= $allowed ?>" data-name="<?= $field['name'] ?>">
                    <input type="file" class="form-field" id="<?= $id ?>">
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
            <?php endif ?>
        <?php endforeach ?>
    </div>
    <button type="button" @click="send()" class="ml-auto learn-more-btn mt-5">
        <?= __("Odoslať", "swslovakia") ?>
        <span class="icon">
            <?= svgIcon(icon_path(false) . "icon-arrow_right.svg") ?>
        </span>
    </button>
<?php endif ?>


<?php

function required($field)
{
    return $field['is_required'] ? 'required' : '';
}

?>