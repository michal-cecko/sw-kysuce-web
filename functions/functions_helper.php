<?php

// CUSTOM MENU FROM WP MENU

function wp_get_menu_array($menu_name)
{
    $locations = get_nav_menu_locations();
    if (!empty($locations[$menu_name])) {
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        $array_menu = wp_get_nav_menu_items($menu, array('order' => 'DESC'));
        $menu = array();
        foreach ($array_menu as $m) {
            //print_r($m);
            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID'] = $m->ID;
                $menu[$m->ID]['title'] = $m->title;
                $menu[$m->ID]['url'] = $m->url;
                $menu[$m->ID]['children'] = array();
                $menu[$m->ID]['class'] = $m->current == TRUE ? 'active' : '';
            }
        }
        $submenu = array();
        foreach ($array_menu as $m) {
            if ($m->menu_item_parent) {
                $submenu[$m->ID] = array();
                $submenu[$m->ID]['ID'] = $m->ID;
                $submenu[$m->ID]['title'] = $m->title;
                $submenu[$m->ID]['url'] = $m->url;
                $submenu[$m->ID]['class'] = $m->current == TRUE ? 'active' : '';
                if ($m->current == TRUE) {
                    $menu[$m->menu_item_parent]['class'] = 'active';
                }
                $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
            }
        }

        return $menu;
    }

    return FALSE;
}

add_filter('wp_get_nav_menu_items', 'prefix_nav_menu_classes', 10, 3);

function prefix_nav_menu_classes($items, $menu, $args)
{
    _wp_menu_item_classes_by_context($items);

    return $items;
}

//ANOTHER WAY OF CHECKING ACTIVE CLASS

/*function check_active_menu_item( $menu_item ) {
    $actual_link = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if ( $actual_link == $menu_item['url'] ) {
        return 'active';
    }
    return '';
}*/

function getNoResultsHTML()
{
    echo "nič sa nenašlo";
}


function svgIcon($path, array $attributes = [])
{
    ob_start();
    include $path;
    $html = ob_get_clean();

    if ($svgTagEndPosition = strpos($html, "<svg") !== false && !empty($attributes)) {
        $attrHtml = "svg";
        foreach ($attributes as $attr => $value) {
            $attrHtml .= " " . $attr . "='" . implode(" ", $value) . "' ";
        }
        $html = substr_replace($html, $attrHtml, $svgTagEndPosition, 0);
        if (strpos($html, "<svgsvg") !== false) $html = str_replace("<svgsvg", "<svg", $html);
    }

    return $html;
}

function get_template_part_as_string($slug, $args = [])
{
    ob_start();
    get_template_part($slug, null, $args);
    $template = ob_get_contents();
    ob_end_clean();
    return $template;
}


function reading_time($id)
{
    $content = get_post_field('post_content', $id);
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);
    return $readingtime;
}


function printMenu($location)
{
    $menu = wp_get_menu_array("footer-links");
    if ($menu) {
        foreach ($menu as $key => $item) { ?>
            <li>
                <a class="<?= $item['class'] ?>" href="<?= esc_url($item["url"]); ?>">
                    <?= esc_attr($item['title']); ?>
                </a>
            </li>
        <?php }
    } else {
        echo "<!-- Menu nebolo nájdené  -->";
    }
}


function get_id_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);

    if(empty($page)) return null;

    return $page->ID;
}


function getPostCategory($post)
{
    $category = get_the_category($post)[0] ?? false;
    if (in_array($category->term_id, [1])) $category = false;
    return $category;
}


// ASSETS PATHS

function image_path($uri = true)
{
    return ($uri ? get_template_directory_uri() : get_template_directory()) . "/assets/images";
}

function icon_path($uri = true)
{
    return ($uri ? get_template_directory_uri() : get_template_directory()) . "/assets/icons/";
}

function script_path($uri = true)
{
    return ($uri ? get_template_directory_uri() : get_template_directory()) . "/assets/js";
}

function favicon_path($uri = true)
{
    return ($uri ? get_template_directory_uri() : get_template_directory()) . "/assets/favicon";
}


function getSharerLink($network, $url)
{
    switch ($network) {
        case "facebook":
            return "https://www.facebook.com/sharer/sharer.php?u=" . $url;
        case "linkedin":
            return "https://www.linkedin.com/sharing/share-offsite/?url=" . $url;
        case "twitter":
            return "https://twitter.com/share?url=" . $url;
        default:
            return "";
    }
}

function get_author_description_by_id($user_id)
{
    $user = get_user_by('ID', $user_id); // Get user object by user ID
    if (!$user) {
        return false; // Return false if user not found
    }

    // Retrieve the 'description' user meta field
    return get_user_meta($user_id, 'description', true);
}

function getMonthName($month)
{
    switch ($month) {
        case 1:
            return "Január";
        case 2:
            return "Február";
        case 3:
            return "Marec";
        case 4:
            return "Apríl";
        case 5:
            return "Máj";
        case 6:
            return "Jún";
        case 7:
            return "Júl";
        case 8:
            return "August";
        case 9:
            return "September";
        case 10:
            return "Október";
        case 11:
            return "November";
        case 12:
            return "December";
    }
}

function getShortMonth($month)
{
    switch ($month) {
        case 1:
            return "Jan";
        case 2:
            return "Feb";
        case 3:
            return "Mar";
        case 4:
            return "Apr";
        case 5:
            return "Máj";
        case 6:
            return "Jún";
        case 7:
            return "Júl";
        case 8:
            return "Aug";
        case 9:
            return "Sep";
        case 10:
            return "Okt";
        case 11:
            return "Nov";
        case 12:
            return "Dec";
    }
}


function getEventDate($event)
{
    ob_start();
    $dateStart = get_field("event_start", $event);
    $dateEnd = get_field("event_end", $event);
    $dayStart = date("d", strtotime($dateStart));
    $monthStart = date("n", strtotime($dateStart));
    $yearStart = date("Y", strtotime($dateStart));
    if (!empty($dateEnd) && $dateEnd !== $dateStart) :
        $dayEnd = date("d", strtotime($dateEnd));
        $yearEnd = date("Y", strtotime($dateEnd));
        $monthEnd = date("n", strtotime($dateEnd));

        if ($monthEnd === $monthStart) : ?>

            <?= $dayStart ?>. - <?= $dayEnd ?>. <?= strtolower(getMonthName($monthStart)) ?> <?= $yearStart ?>

        <?php else : ?>

            <?= $dayStart ?>. <?= strtolower(getMonthName($monthStart)) ?> <?= $yearStart ?> - <?= $dayEnd ?>. <?= strtolower(getMonthName($monthEnd)) ?> <?= $yearEnd ?>

        <?php endif ?>

    <?php else : ?>

        <?= $dayStart ?>. <?= strtolower(getMonthName($monthStart)) ?> <?= $yearStart ?>

    <?php endif;
    return ob_get_clean();
}




function checkCaptcha($responseToken)
{
    if(LOCALHOST) return true;

    $secretKey = '***REMOVED***';

    // Send a POST request to the reCAPTCHA verification API
    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => $secretKey,
        'response' => $responseToken
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response JSON
    $decodedResponse = json_decode($response);

    // Check if the response was successful and the score is high enough
    return $decodedResponse->success && $decodedResponse->score >= 0.5;
}


function validation( $data )
{
    $data = trim( $data );
    $data = stripslashes( $data );
    $data = htmlspecialchars( $data );
    return $data;
}



function remove_directory($directory)
{
    if (!empty($directory) && is_dir($directory)) {
        $files = glob($directory . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Remove individual files
            } elseif (is_dir($file)) {
                remove_directory($file); // Recursively remove subdirectories
            }
        }
        rmdir($directory); // Remove the empty directory
        return true;
    }
    return false;
}