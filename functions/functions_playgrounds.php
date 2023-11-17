<?php



/*
 * IHRISKÁ ---- START
 */

$labels = array(
    'name' => __('Ihriská', 'swslovakia'),
    'singular_name' => __('Ihrisko', 'swslovakia'),
    'add_new' => __('Pridať nové ihrisko', 'swslovakia'),
    'add_new_item' => __('Pridať nové ihrisko', 'swslovakia'),
    'edit_item' => __('Upraviť ihrisko', 'swslovakia'),
    'new_item' => __('Nové ihrisko', 'swslovakia'),
    'view_item' => __('Otvoriť ihrisko', 'swslovakia'),
    'search_items' => __('Hľadať ihrisko', 'swslovakia'),
    'not_found' => __('Ihrisko nebolo nájdené', 'swslovakia'),
    'not_found_in_trash' => __('Ihrisko nebolo nájdené v koši', 'swslovakia')
);

$supports = array(
    'title',
);

$args = array(
    'labels' => $labels,
    'supports' => $supports,
    'public' => TRUE,
    'has_archive' => TRUE,
    'show_in_rest' => TRUE,
    'publicly_queryable' => false, // Turn off single pages
    'taxonomy' => [],
    'menu_icon' => 'dashicons-universal-access',
    'rewrite' => ['slug' => 'playground'],
);

register_post_type('playground', $args);

/*function set_playground_status_pending($post_id) {
    if (get_post_type($post_id) === 'playground' && get_post_status($post_id) !== 'publish') {
        // Set the status to 'pending' when a new post is added or updated
        wp_update_post(array('ID' => $post_id, 'post_status' => 'pending'));
    }
}
add_action('save_post', 'set_playground_status_pending');

function approve_playground_on_publish($new_status, $old_status, $post) {
    if ($post->post_type === 'playground' && $old_status !== 'publish' && $new_status === 'publish') {
        // Set the status to 'publish' when the post is approved
        wp_update_post(array('ID' => $post->ID, 'post_status' => 'publish'));
    }
}
add_action('transition_post_status', 'approve_playground_on_publish', 10, 3);*/

register_post_meta('playground', 'reported_email', ['type' => 'string']);
register_post_meta('playground', 'reported_name', ['type' => 'string']);

add_action('template_redirect', 'sw_redirect_all_playgrounds_singles');

function sw_redirect_all_playgrounds_singles()
{
    if (is_singular('playground')) :
        wp_redirect(home_url(), 301);
        exit;
    endif;
}
