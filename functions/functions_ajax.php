<?php

//BLOG

add_action('wp_ajax_get_posts', 'sw_get_posts');
add_action('wp_ajax_nopriv_get_posts', 'sw_get_posts');

function sw_get_posts()
{
    $postType = ($_GET['post_type'] ?? "") === "post" ? "post" : 'playground';

    $args = [
        'post_type' => $postType,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if($postType === 'post') {
        //TODO later remove
        //$args['post__not_in'] = get_option('sticky_posts');
    }

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
    if($postType === 'post') {
        $final['content'] = get_template_part_as_string("template_parts/blog/blog-archive", ['posts' => $posts]);
    } else {
        $final['content'] = get_template_part_as_string("template_parts/playgrounds/playgrounds-archive", ['posts' => $posts]);
    }

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
        send_playground_request($data, $_FILES);
    }
}

function send_playground_request($data, $images)
{
    if(empty($data['lat']) || empty($data['long']) || empty($data['address']) || empty($data['email']) || empty($images['fotky'])){
        wp_send_json_error("Nevyplnili ste jedno z požadovaných polí.");
    }

    $post_args = [
        'post_type' => 'playground',
        'post_status' => 'pending', // You can change this to 'publish' if you want the post to be published immediately
        'meta_input' => [
            'reported_email' => $data['email'],
            'reported_name' => $data['name'] ?? "",
        ],
    ];

    // Create the post
    $post_id = wp_insert_post($post_args);

    // Check if there are images present
    if (!empty($images['fotky']['tmp_name']) && is_array($images['fotky']['tmp_name'])) {
        // Uploading images and getting their attachment IDs
        $image_ids = array();
        foreach ($images['fotky']['tmp_name'] as $index => $tmp_name) {
            $attachment_id = media_handle_upload('fotky', 0); // Upload the image to the media library
            if (is_wp_error($attachment_id)) {
                wp_send_json_error("Nastala chyba počas nahrávania obrázka.");
            } else {
                $image_ids[] = $attachment_id;
            }
        }

        // Update the ACF gallery field "pg-images" with the image IDs
        update_field('pg-images', $image_ids, $post_id);
    } else {
        wp_send_json_error("Nepridali ste žiadne fotky.");
    }

    // Update the ACF fields "pg-location" and "pg-address"
    update_field('pg-location', ['lat' => $data['lat'], 'lng' => $data['long']], $post_id);
    update_field('pg-address', $data['address'], $post_id);

    if ($post_id) {
        wp_send_json_success("Ihrisko bolo odoslané na schválenie.");
    } else {
        wp_send_json_error("Nepodarilo sa odoslať žiadosť na schválenie ihriska. #3");
    }
}

function send_contact_form($data) {
    if(empty($data['meno']) || empty($data['sprava']) || empty($data['email'])){
        wp_send_json_error("Nevyplnili ste jedno z požadovaných polí.");
    }

    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $body = get_template_part_as_string("template_parts/emails/submitted-contact-form", ['data' => $data]);
    $subject = "SW Kysuce | Nová správa z kontaktného formulára";
    $recipient = get_field("contact_form_email", get_id_by_slug("kontakt"));

    if(wp_mail($recipient, $subject, $body, $headers)) {
        wp_send_json_success("Formulár bol úspešne odoslaný.");
    } else {
        wp_send_json_error("Nepodarilo sa odoslať email. #3");
    }
}