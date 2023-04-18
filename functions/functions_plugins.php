<?php

// Move Yoast to bottom
function theme_move_yoast()
{
    return 'low';
}

add_filter('wpseo_metabox_prio', 'theme_move_yoast');