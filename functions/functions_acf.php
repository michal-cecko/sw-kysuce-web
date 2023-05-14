<?php

// OPTIONS PAGES

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(
        array(
            'page_title' => 'Nastavenia stránky',
            'menu_title' => 'Nastavenia stránky',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'manage_options',
            'redirect' => FALSE
        ));

    acf_add_options_sub_page(
        array(
            'page_title' => 'Nastavenia sekcii',
            'menu_title' => 'Nastavenia sekcii',
            'parent_slug' => 'theme-general-settings',
        ));
}