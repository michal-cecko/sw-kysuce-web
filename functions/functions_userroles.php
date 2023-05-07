<?php

// add "usporiadatel" role
function add_usporiadatel_role()
{
    $admin_caps = get_role('author')->capabilities;
    add_role('usporiadatel', 'Usporiadateľ', $admin_caps);
}

add_action('init', 'add_usporiadatel_role');

//Restrict role
function restrict_usporiadatel_role()
{
    $user = wp_get_current_user();
    if (in_array('usporiadatel', $user->roles)) {
        remove_menu_page('upload.php');         // Plugins
        remove_menu_page('users.php');         // Plugins
        remove_menu_page('plugins.php');         // Plugins
        remove_menu_page('options-general.php'); // Settings
        remove_menu_page('edit.php?post_type=acf-field-group'); // ACF Fields
        remove_menu_page('ai1wm_export');         // All in one WP Migration
        remove_menu_page('tools.php');              // Site Health
        remove_menu_page('themes.php');          // Appearance
    }
}
add_action('admin_menu', 'restrict_usporiadatel_role');