<?php

function modify_image_sizes($sizes) {
    // Define the sizes you want to keep
    $keep_sizes = array(
        'medium',
        'full',
    );

    // Remove sizes not in the $keep_sizes array
    foreach ($sizes as $size => $properties) {
        if (!in_array($size, $keep_sizes)) {
            unset($sizes[$size]);
        }
    }

    return $sizes;
}

//add_filter('intermediate_image_sizes_advanced', 'modify_image_sizes');