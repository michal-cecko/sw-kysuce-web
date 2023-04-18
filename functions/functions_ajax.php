<?php
//AJAX

add_action('wp_ajax_delete_form', 'delete_submitted_form');
add_action('wp_ajax_nopriv_delete_form', 'delete_submitted_form');

function delete_submitted_form()
{
    wp_send_json([], 200);
}


//BLOG

add_action('wp_ajax_get_posts', 'sw_get_posts');
add_action('wp_ajax_nopriv_get_posts', 'sw_get_posts');

function sw_get_posts()
{
    $stickyPosts = get_option('sticky_posts');

    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'date',
        'post__not_in' => $stickyPosts,
        'order' => 'DESC',
    ];

    //Fetch main from category
    $postsPerPage = $_GET['posts_per_page'] ?? -1;

    if ($postsPerPage > 0) {
        $page = $_GET['page'] ?? 1;
        $args['paged'] = $page;
    }

    $args['posts_per_page'] = $postsPerPage;

    $activeCategory = $_GET['category'] ?? false;
    if ($activeCategory && $activeCategory != -1) {
        $args['category__in'] = [$activeCategory];
    }

    $posts = new WP_Query($args);

    $final = [];

    $final['pagination']['total_pages'] = $posts->max_num_pages;
    $final['content'] = get_template_part_as_string("template_parts/blog/blog-archive", ['posts' => $posts]);

    wp_send_json($final, 200);
}