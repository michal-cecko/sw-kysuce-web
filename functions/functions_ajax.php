<?php

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


//EVENTS

add_action('wp_ajax_get_events_by_year', 'sw_get_events_by_year');
add_action('wp_ajax_nopriv_get_events_by_year', 'sw_get_events_by_year');

function sw_get_events_by_year()
{
    $year = $_GET['year'] ?? false;

    if (!$year) {
        wp_send_json_error("Nebol zadaný rok.");
    }

    $args = [
        'post_type' => 'event',
        'post_status' => 'publish',
        'orderby' => 'date',
        'posts_per_page' => -1,
        'order' => 'DESC',
        'meta_query' => [
            "relation" => "AND",
            [
                'key' => 'event_start',
                'value' => [$year . '-01-01', $year . '-12-31'],
                'compare' => 'BETWEEN',
                'type' => 'DATE',
            ],
            [
                'key' => 'event_start',
                'value' => date('Y-m-d'),
                'compare' => '<',
                'type' => 'DATE'
            ]
        ],
    ];

    $posts = new WP_Query($args);
    $final = [];
    $final['content'] = get_template_part_as_string("template_parts/events/past-events-archive", ['events' => $posts]);

    wp_send_json($final, 200);
}



//CONTACT

add_action('wp_ajax_submit_contact_form', 'sw_submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'sw_submit_contact_form');

function sw_submit_contact_form()
{
    $type = $_POST['type'] ?? 'contact';
    $data = $_POST['data'];
    $recaptcha = $_POST['recaptcha'] ?? false;

    if (!checkCaptcha($recaptcha)) {
        wp_send_json_error("Myslíme si, že ste robot. (#2)");
    }

    if ($type === "contact") {
        send_contact_form($data);
    } else {
        wp_send_json_error("Funkcionalita ihrísk bude dopracovaná čoskoro.");
    }
}

function send_contact_form($data) {
    if(empty($data['meno']) || empty($data['sprava']) || empty($data['email'])){
        wp_send_json_error("Nevyplnili ste jedno z požadovaných polí.");
    }

    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $body = get_template_part_as_string("template_parts/emails/submitted-contact-form", ['data' => $data]);
    $subject = "Nová správa z webu SW Slovakia";
    $recipient = get_field("contact_form_email", get_id_by_slug("kontakt"));

    if(wp_mail($recipient, $subject, $body, $headers)) {
        wp_send_json_success("Formulár bol úspešne odoslaný.");
    } else {
        wp_send_json_error("Nepodarilo sa odoslať email. #3");
    }
}