<?php
$ip = $_SERVER['REMOTE_ADDR'];
if ($ip == '127.0.0.1' or $ip == '::1') {
    define('LOCALHOST', true);
} else {
    define('LOCALHOST', false);
}

date_default_timezone_set('Europe/Bratislava'); // or your timezone

const WP_VERSIONING = false && !LOCALHOST;
$version = wp_get_theme()->get("Version") ?? "1.1.0";
DEFINE("VERSION", !WP_VERSIONING ? time() : $version);

// THEME SETUP

if (is_user_logged_in()) {
    show_admin_bar(true);
}

add_action('after_setup_theme', 'theme_setup');
function theme_setup(): void
{
    //Theme support
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
    add_theme_support('custom-logo', array('class' => 'custom-logo'));
    remove_theme_support("core-block-patterns");

    //register menus
    register_nav_menus([
        'header-links' => 'Navigácia v hlavičke',
        'footer-links' => 'Linky v pätičke',
    ]);
}

// THEME CLEANUP
add_action('init', 'theme_cleanup', 9999);
function theme_cleanup(): void
{
    //TURN OFF COMMENTS
    //Redirect any user trying to access comments page
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }

    //Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });
}

// ENQUEUE / DEQUEUE SCRIPTS

const COMPONENT_PREFIX = "comp-";

add_filter('script_loader_tag', 'add_type_attribute', 50, 3);
function add_type_attribute($tag, $handle, $src)
{
    // if not your script, do nothing and return original $tag
    if (!str_contains($handle, COMPONENT_PREFIX) && !in_array($handle, ["general-js", 'admin-js'])) {
        return $tag;
    }

    // load component as module.
    return '<script type="module" src="' . esc_url($src) . '"></script>';
}

function enqueue_component($name, $defaultPHPVars = [], ...$moreVals)
{
    $handle = COMPONENT_PREFIX . $name;
    wp_register_script($handle, get_template_directory_uri() . "/dist/js/components/" . $name . ".min.js", [], VERSION, true);
    wp_enqueue_script($handle);

    $vars = array_merge($moreVals, $defaultPHPVars);
    if (empty($vars)) return;
    wp_localize_script($handle, 'PHPVars', $vars);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts_links');
function enqueue_custom_scripts_links(): void
{
    $defaultPHPVars = [
        'homeUrl' => get_home_url(),
        'ajaxUrl' => admin_url('admin-ajax.php')
    ];

    //JS

    //DEFAULTS
    wp_enqueue_script('axios-js', 'https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.4/axios.min.js', 'axios-js');
    wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.5/swiper-bundle.min.js');
    wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.5/swiper-bundle.css');
    wp_enqueue_script('lordicon-js', 'https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js');
    wp_enqueue_script('aos-js', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js');
    wp_enqueue_script('fslightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.0.9/index.min.js');
    wp_enqueue_script('lazyload-js', get_template_directory_uri() . '/assets/js/libs/lazyload.min.js');

    $api_key = 'YOUR_GOOGLE_MAPS_API_KEY';
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array(), null, true);

    if(is_page_template("template-blog.php")) {
        wp_enqueue_style('slick-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css');
        wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css');
        wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', ['jquery']);
    }


    wp_register_script(
        "general-js",
        get_template_directory_uri() . '/dist/js/general.min.js',
        ['jquery'],
        VERSION,
        TRUE
    );
    wp_enqueue_script('general-js');


    //COMPONENTS

    global $post;
    $currentPageSlug = $post?->post_name;

    enqueue_component("commons", $defaultPHPVars);
    enqueue_component("header");

    if (in_array($currentPageSlug, ['blog', 'ihriska'])) {
        enqueue_component("blog");
    }

    if ($currentPageSlug == "kontakt") {
        enqueue_component("contact");
    }

    if ($currentPageSlug == "podujatia-a-sutaze") {
        enqueue_component("events");
    }

    if ( is_singular("event") ) {
        wp_enqueue_script('flipdown-js', get_template_directory_uri() . '/assets/js/libs/flipdown/flipdown.min.js');
        wp_enqueue_style('flipdown-css', get_template_directory_uri() . '/assets/css/flipdown.min.css', [], VERSION);
        enqueue_component("event");
    }

    if(is_singular('event') || $currentPageSlug == "kontakt") {
        $recaptchaSiteKey = "***REMOVED***";
        wp_enqueue_script('recaptcha-js', 'https://www.google.com/recaptcha/api.js?render=' . $recaptchaSiteKey);
    }

    enqueue_component("tooltip");

    //STYLES

    //Remove global inline styles
    wp_dequeue_style('global-styles');

    wp_register_style('main-css', get_template_directory_uri() . '/dist/css/main.css', [], VERSION);
    wp_enqueue_style('main-css');
}

function admin_enqueue_scripts()
{
    $user = wp_get_current_user();

    $defaultPHPVars = [
        'homeUrl' => get_home_url(),
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce')
    ];

    wp_register_style('admin-css', get_template_directory_uri() . '/dist/css/admin/admin.css', FALSE, time());
    wp_enqueue_style('admin-css');

    if( in_array('usporiadatel', $user->roles) ){
        wp_enqueue_style('usporiadatel-css', get_template_directory_uri() . '/dist/css/admin/usporiadatel.css', FALSE, time());
    }

    if( in_array('author', $user->roles) ){
        wp_enqueue_style('author-css', get_template_directory_uri() . '/dist/css/admin/author.css', FALSE, time());
    }

    wp_register_script(
        "admin-js",
        get_template_directory_uri() . '/dist/js/admin.min.js',
        false,
        time(),
        TRUE
    );
    wp_enqueue_script('admin-js');
    wp_localize_script('admin-js', 'PHPVars', $defaultPHPVars);
}

add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');