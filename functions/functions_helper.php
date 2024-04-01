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
    if(is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = validation($value);
        }
        return $data;
    }

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

function replace_constants(array $constants, string $text) {
    foreach ($constants as $key => $value) {
        $text = str_replace("_#$key#_", $value, $text);
    }
    return $text;
}

function custom_remove_accents($string)
{
    if (!preg_match('/[\x80-\xff]/', $string))
        return $string;

    $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
        chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
        chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
        chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
        chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
        chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
        chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
        chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
        chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
        chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
        chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
        chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
        chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
        chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
        chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
        chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
        chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
        chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
        chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
        chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
        chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
        chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
        chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
        chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
        chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
        chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
        chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
        chr(195) . chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
        chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
        chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
        chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
        chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
        chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
        chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
        chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
        chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
        chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
        chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
        chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
        chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
        chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
        chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
        chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
        chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
        chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
        chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
        chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
        chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
        chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
        chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
        chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
        chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
        chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
        chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
        chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
        chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
        chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
        chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
        chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
        chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
        chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
        chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
        chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
        chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
        chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
        chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
        chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
        chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
        chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
        chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
        chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
        chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
        chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
        chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
        chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
        chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
        chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
        chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
        chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
        chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
        chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
        chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
        chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
        chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
        chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
        chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
        chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
        chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
        chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
        chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
        chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

function decodeUnicodeString($str) {
    // Mapping array for Unicode to accented characters
    $unicode_to_accented = [
        'u00e1' => 'á',
        'u00e9' => 'é',
        'u00ed' => 'í',
        'u00f3' => 'ó',
        'u00fa' => 'ú',
        'u00e4' => 'ä',
        'u00ef' => 'ï',
        'u00fc' => 'ü',
        'u00ff' => 'ÿ',
        'u00f1' => 'ñ',
        'u0161' => 'š',
        'u010d' => 'č',
        'u0165' => 'ť',
        'u017e' => 'ž',
        'u00fd' => 'ý',
        'u00cb' => 'Ë',
        'u00cf' => 'Ï',
        'u00d6' => 'Ö',
        'u00dc' => 'Ü',
        'u00d1' => 'Ñ',
        'u0160' => 'Š',
        'u010c' => 'Č',
        'u0164' => 'Ť',
        'u017d' => 'Ž',
        'u00dd' => 'Ý',
        'u00d3' => 'Ó',
        'u00c1' => 'Á',
        'u00cd' => 'Í',
        'u00c9' => 'É',
        'u00da' => 'Ú',
        'u00c4' => 'Ä',
        'u0147' => 'ň',
        'u00d4' => 'Ô',
        'u013e' => 'ľ',
        'u00f4' => 'ô',
        'ud83e' => '',
        'u011b' => 'ě',
        'u016f' => 'ů',
        'u0159' => 'ř',
        'u00a0' => ' ',
    ];

    // Perform the replacement
    $decoded_str = strtr($str, $unicode_to_accented);

    return $decoded_str;
}
