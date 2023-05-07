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

    register_taxonomy('event-tag', array("event"), array(
        'description' => 'Tagy udalostí',
        'labels' => array(
            'name' => __('Tagy', 'swslovakia'),
            'singular_name' => __('Tag', 'swslovakia'),
            'search_items' => __('Vyhľadať tag', 'swslovakia'),
            'popular_items' => __('Populárne tagy', 'swslovakia'),
            'all_items' => __('Všetky tagy', 'swslovakia'),
            'parent_item' => __('Nadradený tag', 'swslovakia'),
            'parent_item_colon' => __('Nadradený tag:', 'swslovakia'),
            'edit_item' => __('Upraviť tag', 'swslovakia'),
            'view_item' => __('Zobraziť tag', 'swslovakia'),
            'update_item' => __('Zmeniť tag', 'swslovakia'),
            'add_new_item' => __('Pridať nový tag', 'swslovakia'),
            'new_item_name' => __('Pridať názov nového tagu', 'swslovakia'),
            'separate_items_with_commas' => __('Oddeľte tagy čiarkou', 'swslovakia'),
            'add_or_remove_items' => __('Pridať alebo odstrániť tagy', 'swslovakia'),
            'choose_from_most_used' => __('Vyberte z najpoužívanejších tagov', 'swslovakia'),
            'not_found' => __('Neboli nájdené žiadne tagy', 'swslovakia'),
        ),
        'public' => TRUE,
        'show_ui' => TRUE,
        'show_in_nav_menus' => TRUE,
        'show_tagcloud' => TRUE,
        'meta_box_cb' => NULL,
        'show_admin_column' => FALSE,
        'hierarchical' => FALSE,
        'query_var' => 'event-tag',
        'rewrite' => array(
            'slug' => 'event-tag',
            'with_front' => TRUE,
            'hierarchical' => FALSE,
        ),
        'capabilities' => array(),
    ));

    /*
     * EVENTY ---- END
     */



    //-----------------------------------------------------------------------------------------
}

add_action('init', 'create_post_types');