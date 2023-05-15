<?php

/*
 * Plugin name: Custom Gutenberg blocks
 * Description: Blocks: Winners block
 * Author: Michal Čečko
 */
function custom_block_js_register() {
    wp_enqueue_script(
        "custom_block_js_register",
        plugin_dir_url(__FILE__) . "block.js",
        array( "wp-blocks", "wp-editor", 'wp-element' ),
        true
    );
}

add_action( "enqueue_block_editor_assets", "custom_block_js_register" );


register_block_type(
    'block/placement-block', array(
        'render_callback' => 'placement_block_render',
    )
);


function placement_block_render($attributes) {
    $firstPlaceName = $attributes['firstPlaceName'] ?? '';
    $firstPlaceDesc = $attributes['firstPlaceDesc'] ?? '';
    $secondPlaceName = $attributes['secondPlaceName'] ?? '';
    $secondPlaceDesc = $attributes['secondPlaceDesc'] ?? '';
    $thirdPlaceName = $attributes['thirdPlaceName'] ?? '';
    $thirdPlaceDesc = $attributes['thirdPlaceDesc'] ?? '';
    ob_start();
    include plugin_dir_path(__FILE__) . "templates/placement.php";
    return ob_get_clean();
}