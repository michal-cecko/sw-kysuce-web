<?php

// CREATING CUSTOM POST TYPES

function create_post_types()
{


    /*
     * EVENTY ---- START
     */

    $labels = array(
        'name' => __('Podujatia', 'swslovakia'),
        'singular_name' => __('Podujatie', 'swslovakia'),
        'add_new' => __('Pridať nové podujatie', 'swslovakia'),
        'add_new_item' => __('Pridať nové podujatie', 'swslovakia'),
        'edit_item' => __('Upraviť podujatie', 'swslovakia'),
        'new_item' => __('Nové podujatie', 'swslovakia'),
        'view_item' => __('Otvoriť podujatie', 'swslovakia'),
        'search_items' => __('Hľadať podujatie', 'swslovakia'),
        'not_found' => __('Podujatie nebolo nájdené', 'swslovakia'),
        'not_found_in_trash' => __('Podujatie nebolo nájdené v koši', 'swslovakia')
    );

    $supports = array(
        'title',
    );

    $args = array(
        'labels' => $labels,
        'supports' => $supports,
        'public' => TRUE,
        'has_archive' => FALSE,
        'show_in_rest' => TRUE,
        'taxonomy' => [],
        'menu_icon' => 'dashicons-calendar-alt',
        'rewrite' => ['slug' => 'event'],
    );

    register_post_type('event', $args);


    /*
     * EVENTY ---- END
     */


    //-----------------------------------------------------------------------------------------


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
    add_action( 'add_meta_boxes', 'sw_register_submitted_forms_meta_box' );
    function sw_register_submitted_forms_meta_box() {
        add_meta_box( 'sw-submitted_forms', __( 'Odoslané formuláre', 'swslovakia' ), 'sw_print_submitted_forms', 'form' );
    }
    function sw_print_submitted_forms( $submittedForm ) {
        $rows = /*json_decode(*/get_post_meta($submittedForm->ID, "submitted_forms");/*, true);*/
        get_template_part("template_parts/admin/submitted_form-rows", "", compact("rows"));
    }

    /*
     * FORMULÁRE ---- END
     */


    //-----------------------------------------------------------------------------------------




    /*register_taxonomy('predmety', array("doucovatel"), array(
        'description' => 'Popisčok',
        'labels' => array(
            'name' => _x('Predmety', 'Predmety', 'swslovakia'),
            'singular_name' => _x('Predmet', 'Predmet', 'swslovakia'),
            'search_items' => __('Vyhľadať predmet', 'swslovakia'),
            'popular_items' => __('Populárne predmety', 'swslovakia'),
            'all_items' => __('Všetky predmety', 'swslovakia'),
            'parent_item' => __('Nadradený predmet', 'swslovakia'),
            'parent_item_colon' => __('Nadradený predmet:', 'swslovakia'),
            'edit_item' => __('Upraviť predmet', 'swslovakia'),
            'view_item' => __('Zobraziť predmet', 'swslovakia'),
            'update_item' => __('Zmeniť predmet', 'swslovakia'),
            'add_new_item' => __('Pridať nový predmet', 'swslovakia'),
            'new_item_name' => __('Pridať názov nového predmetu', 'swslovakia'),
            'separate_items_with_commas' => __('Oddeľte predmety čiarkou', 'swslovakia'),
            'add_or_remove_items' => __('Pridať alebo odstrániť predmety', 'swslovakia'),
            'choose_from_most_used' => __('Vyberte z najpoužívanejších predmetov', 'swslovakia'),
            'not_found' => __('Neboli nájdené žiadne predmety', 'swslovakia'),
        ),
        'public' => TRUE,
        'show_ui' => TRUE,
        'show_in_nav_menus' => TRUE,
        'show_tagcloud' => TRUE,
        'meta_box_cb' => NULL,
        'show_admin_column' => FALSE,
        'hierarchical' => FALSE,
        'query_var' => 'predmety',
        'rewrite' => array(
            'slug' => 'predmety',
            'with_front' => TRUE,
            'hierarchical' => TRUE,
        ),
        'capabilities' => array(),
    ));*/
}

add_action('init', 'create_post_types');