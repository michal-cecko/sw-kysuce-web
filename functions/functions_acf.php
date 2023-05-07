<?php

    // OPTIONS PAGES

    if ( function_exists( 'acf_add_options_page' ) ) {

        acf_add_options_page(
            array(
                'page_title' => 'Nastavenia stránky',
                'menu_title' => 'Nastavenia stránky',
                'menu_slug'  => 'theme-general-settings',
                'capability' => 'manage_options',
                'redirect'   => FALSE
            ) );

        acf_add_options_sub_page(
            array(
                'page_title'  => 'Nastavenia sekcii',
                'menu_title'  => 'Nastavenia sekcii',
                'parent_slug' => 'theme-general-settings',
            ) );
    }


function acf_populate_subject_price_select( $field ) {
    // reset choices
    $field['choices'] = array();
    global $post;

    if($post) {
        $subjects = get_the_terms( $post->ID,"predmety");

        // loop through array and add to field 'choices'
        if( is_array($subjects) ) {
            foreach( $subjects as $subject ) {
                $field['choices'][ $subject->term_id ] = $subject->name;
            }
        }
    }

    // return the field
    return $field;
}

add_filter('acf/load_field/key=field_634a7b08007cd', 'acf_populate_subject_price_select');