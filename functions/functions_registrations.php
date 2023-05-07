<?php


/*
* FORMULÁRE ---- START
*/

$labels = array(
    'name' => __('Formuláre', 'swslovakia'),
    'singular_name' => __('Formulár', 'swslovakia'),
    'add_new' => __('Pridať nový formulár', 'swslovakia'),
    'add_new_item' => __('Pridať nový formulár', 'swslovakia'),
    'edit_item' => __('Upraviť formulár', 'swslovakia'),
    'new_item' => __('Nový formulár', 'swslovakia'),
    'view_item' => __('Otvoriť formulár', 'swslovakia'),
    'search_items' => __('Hľadať formulár', 'swslovakia'),
    'not_found' => __('Formulár nebol nájdený', 'swslovakia'),
    'not_found_in_trash' => __('Formulár nebol nájdený v koši', 'swslovakia')
);

$supports = array(
    'title',
    'custom-fields'
);

$args = array(
    'labels' => $labels,
    'supports' => $supports,
    'public' => TRUE,
    'has_archive' => FALSE,
    'show_in_rest' => TRUE,
    'taxonomy' => [],
    'menu_icon' => 'dashicons-forms',
    'rewrite' => ['slug' => 'form'],
);

register_post_type('form', $args);


/**
 * REGISTER SUBMITTED FORMS META
 */
$args = [
    'type' => 'string',
];
register_post_meta('form', 'submitted_forms', $args);


/**
 * REGISTER META BOX.
 */
add_action('add_meta_boxes', 'sw_register_submitted_forms_meta_box');
function sw_register_submitted_forms_meta_box()
{
    add_meta_box('sw-submitted_forms', __('Odoslané formuláre', 'swslovakia'), 'sw_print_submitted_forms', 'form');
}

function sw_print_submitted_forms($submittedForm)
{
    $rows = /*json_decode(*/
        get_post_meta($submittedForm->ID, "submitted_forms");/*, true);*/
    get_template_part("template_parts/admin/submitted_form-rows", "", compact("rows"));
}