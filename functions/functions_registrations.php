<?php


/*
* FORMULÁRE ---- START
*/

$labels = array(
    'name' => __('Formuláre', 'swslovakia'),
    'singular_name' => __('Formulár', 'swslovakia'),
    'add_new' => __('Pridať nový formulár', 'swslovakia'),
    'add_new_item' => __('Pridať nový formulár', 'swslovakia'),
    'edit_item' => __('Upraviť formulár', 'swslovakia'),
    'new_item' => __('Nový formulár', 'swslovakia'),
    'view_item' => __('Otvoriť formulár', 'swslovakia'),
    'search_items' => __('Hľadať formulár', 'swslovakia'),
    'not_found' => __('Formulár nebol nájdený', 'swslovakia'),
    'not_found_in_trash' => __('Formulár nebol nájdený v koši', 'swslovakia')
);

$supports = array(
    'title',
    'custom-fields'
);

$args = array(
    'labels' => $labels,
    'supports' => $supports,
    'public' => TRUE,
    'has_archive' => FALSE,
    'show_in_rest' => TRUE,
    'taxonomy' => [],
    'menu_icon' => 'dashicons-forms',
    'rewrite' => ['slug' => 'form'],
);

register_post_type('form', $args);

function disable_single_pages_for_registrations()
{
    if (is_single()) {
        $postType = get_post_type();

        if ($postType === 'form') {
            wp_redirect(home_url());
            exit;
        } elseif ($postType === 'event' && checkIfEventHasPassed(get_the_ID())) {
            wp_redirect(home_url());
            exit;
        }
    }
}

function checkIfEventHasPassed($id)
{
    $eventStart = get_field("event_start", $id);
    $eventStartTimestamp = strtotime($eventStart);
    return $eventStartTimestamp <= time();
}


add_action('template_redirect', 'disable_single_pages_for_registrations');


/**
 * REGISTER SUBMITTED FORMS META
 */
$args = [
    'type' => 'string',
];
register_post_meta('form', 'submitted_forms', $args);


/**
 * REGISTER META BOX.
 */
add_action('add_meta_boxes', 'sw_register_submitted_forms_meta_box');
function sw_register_submitted_forms_meta_box()
{
    add_meta_box('sw-submitted_forms', __('Odoslané formuláre', 'swslovakia'), 'sw_print_submitted_forms', 'form');
}

function sw_print_submitted_forms($form)
{
    $rows = json_decode(decodeUnicodeString(get_post_meta($form->ID, "submitted_forms", true)), true);
    get_template_part("template_parts/admin/submitted_form-rows", "", compact("rows", "form"));
}


// FORM SUBMIT HANDLER
add_action('wp_ajax_submit_register_form', 'sw_submit_register_form');
add_action('wp_ajax_nopriv_submit_register_form', 'sw_submit_register_form');

function sw_submit_register_form()
{
    $formID = $_POST['form_id'];
    $eventID = $_POST['id'];
    $captcha = $_POST['recaptcha'];
    $fields = $_POST['fields'];

    $registerForm = get_field("event_register_form", $eventID);
    if ($registerForm->ID != $formID) {
        wp_send_json_error("Nastala neočakávaná chyba. Skúste to prosím neskôr. (#1)");
    }

    if (checkIfEventHasPassed($eventID)) {
        wp_send_json_error("Tento event už začal a nieje možné sa naň zaregistrovať. (#2)");
    }

    if (!checkCaptcha($captcha)) {
        wp_send_json_error("Myslíme si, že ste robot. (#2)");
    }

    $fieldsSanitized = [];
    $checkUniqueEmail = false;
    foreach ($fields as $key => $value) {
        $fieldsSanitized[$key] = validation($value);
        if (str_contains($key, "email")) {
            $checkUniqueEmail = true;
        }
    }

    $alreadySubmitted = json_decode(get_post_meta($formID, "submitted_forms", true), true);
    $nextID = 1;

    if (empty($alreadySubmitted)) {
        $alreadySubmitted = [];
    } else {
        foreach ($alreadySubmitted as $submission) {
            if (!empty($submission['id'])) {
                if ($nextID <= $submission['id']) {
                    $nextID = intval($submission['id']) + 1;
                }
            }
            //TODO REMOVE LATER FALSE
            if (false && $checkUniqueEmail) {
                foreach ($submission as $field => $value) {
                    if (str_contains($field, "email") && $value == $fields[$field]) {
                        wp_send_json_error("Tento email už je zaregistrovaný na tento event. (#2)");
                    }
                }
            }
        }
    }

    $filesToSave = [];
    foreach ($_FILES as $file) {
        foreach ($file['name'] as $fieldKey => $fileName) {
            $arr = explode(".", $fileName);
            $filesToSave[$fieldKey] = [
                'name' => $fileName,
                'image_type' => end($arr),
                'tmp_name' => $file['tmp_name'][$fieldKey],
                'full_path' => $file['full_path'][$fieldKey],
                'type' => $file['type'][$fieldKey],
                'error' => $file['error'][$fieldKey],
                'size' => $file['size'][$fieldKey],
            ];
        }
    }

    $filesSaved = [];
    foreach ($filesToSave as $fieldKey => $fileToSave) {
        if ($savedURL = save_uploaded_file($fieldKey, $fileToSave, $nextID, $formID)) {
            $filesSaved[$fieldKey] = $savedURL;
        }
    }

    $finalData = array_merge($filesSaved, $fieldsSanitized, [
        'datum_registracie' => date("d.m.Y H:i"),
        'id' => $nextID
    ]);
    $alreadySubmitted[] = $finalData;

    if (update_post_meta($formID, 'submitted_forms', json_encode($alreadySubmitted))) {

        if (!send_email_to_participant($eventID, $formID, $fieldsSanitized, $filesSaved, $nextID)) {
            wp_send_json_error("Nepodarilo sa odoslať registračný email. Prosím skúste to neskôr.");
        }

        wp_send_json_success("Ďakujeme, boli ste úspešne zaregistrovaní na toto podujatie.");
    }

    wp_send_json_error("Nepodarilo sa Vás zaregistrovať na tento email. Prosím kontaktujte nás pre viac informácii.");
}


function send_email_to_participant($eventID, $formID, $submittedFields, $filesSaved, $submissionID)
{
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $template = prepare_participant_email($eventID, $formID, $submittedFields, $filesSaved, $submissionID);
    $subject = "SW Slovakia Registrácia | " . get_the_title($eventID);
    $recipient = get_field("registrations_recipient", $formID);

    return wp_mail($recipient, $subject, $template, $headers);
}

function prepare_participant_email($eventID, $formID, $submittedFields, $filesSaved, $submissionID)
{
    $body = get_field("competitor_email_body", $formID);
    $fieldNames = get_field_names_from_flexible_content(get_field("form_fields", $formID));
    $suhrnRegistracie = get_template_part_as_string("template_parts/emails/parts/registration-summary", [
        'data' => array_merge(
            $submittedFields,
            $filesSaved
        ),
        'fieldNames' => $fieldNames
    ]);

    $body = replace_constants([
        'MENO' => $submittedFields['meno'],
        'PRIEZVISKO' => $submittedFields['priezvisko'],
        'SUHRN_REGISTRACIE' => $suhrnRegistracie,
    ], $body);

    $hasRegisterFee = get_field("has_register_fee", $formID);
    if ($hasRegisterFee) {
        $suhrnPlatby = get_suhrn_platby($formID, $submittedFields, $submissionID);
        $body = replace_constants([
            'SUHRN_PLATBY' => $suhrnPlatby,
        ], $body);
    }

    $buttons = get_field('externe_odkazy', $formID);
    $buttons[] = ['url' => 'https://google.sk/', 'name' => "Pridať do kalendára", 'color' => '#f59542'];
    $template = get_template_part_as_string("template_parts/emails/templates/default-email-template", ['data' => [
        'title' => get_the_title($eventID),
        'subtitle' => "Súhrn vašej registrácie",
        'content' => $body,
        'buttons' => $buttons
    ]]);

    return $template;
}

function get_field_names_from_flexible_content($flexibleContent) {
    $fieldNames = [];
    foreach ($flexibleContent as $field) {
        $fieldNames[sanitize_title($field['name'])] = $field['name'];
    }
    return $fieldNames;
}


function get_suhrn_platby($formID, $submittedFields, $submissionID)
{
    $paymentInfo = get_field("payment_info", $formID);
    $note = replace_constants([
        'MENO' => $submittedFields['meno'],
        'PRIEZVISKO' => $submittedFields['priezvisko'],
    ], $paymentInfo['pay_by_square_note']);

    $payBySquareBase64 = 'data:image/png;base64,' . base64_encode(generate_pay_by_square_qr($paymentInfo['iban'], $paymentInfo['amount'], $note));
    $payBySquare = save_uploaded_file("pay_by_square", $payBySquareBase64, $submissionID, $formID);
    return get_template_part_as_string("template_parts/emails/parts/payment-summary", [
        'data' => array_merge(
            $paymentInfo,
            [
                'note' => $note,
                'support_email' => get_field("registrations_recipient", $formID),
                'meno' => $submittedFields['meno'],
                'priezvisko' => $submittedFields['priezvisko'],
                'submission_id' => $submissionID,
                'pay_by_square' => $payBySquare
            ]
        )
    ]);
}

function get_file_from_base64($data)
{
    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
        $data = substr($data, strpos($data, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
            throw new \Exception('invalid image type');
        }
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);

        if ($data === false) {
            throw new \Exception('base64_decode failed');
        }
    } else {
        throw new \Exception('did not match data URI with image data');
    }

    return ['image_data' => $data, 'image_type' => $type];
}

function save_uploaded_file($filename_to_save, $file, $submissionID, $formID)
{
    // Create directory for the submission
    $submissionDirectory = wp_upload_dir()['basedir'] . '/form_uploads/' . $formID . '/' . $submissionID;
    if (!wp_mkdir_p($submissionDirectory)) {
        return false; // Unable to create directory, handle accordingly
    }

    // Check if $file is a base64 encoded string
    if (is_string($file) && str_contains($file, ";base64")) {
        try {
            $file = get_file_from_base64($file);
        } catch (Exception $e) {
            return false; // Handle the exception gracefully
        }

        // Get the file extension from the original filename
        $filename_to_save = $filename_to_save . "." . $file['image_type'];

        // Generate unique filename for the final file
        $filename = wp_unique_filename($submissionDirectory, $filename_to_save);

        // Construct final file path
        $finalFilePath = $submissionDirectory . '/' . $filename;

        // Save the base64 decoded file directly to the final folder
        if (!file_put_contents($finalFilePath, $file['image_data']) || !chmod($finalFilePath, 0777)) {
            return false; // Unable to save file or set permissions, handle accordingly
        }

    } else {
        // If $file is not base64 encoded, proceed with regular file saving logic

        $filename_to_save = $filename_to_save . "." . $file['image_type'];

        // Generate unique file name
        $filename = wp_unique_filename($submissionDirectory, $filename_to_save);

        // Move the uploaded file to the submission directory
        $tmpPath = $file['tmp_name'];
        $finalFilePath = $submissionDirectory . '/' . $filename;


        if (!file_exists($tmpPath) || !move_uploaded_file($tmpPath, $finalFilePath)) {
            return false; // Unable to move file, handle accordingly
        }
    }

    return wp_upload_dir()['baseurl'] . '/form_uploads/' . $formID . '/' . $submissionID . '/' . $filename;
}


add_action('wp_ajax_delete_form', 'delete_submitted_form');
add_action('wp_ajax_nopriv_delete_form', 'delete_submitted_form');

function delete_submitted_form()
{
    $formID = $_POST['form_id'] ?? false;
    $submissionID = $_POST['id'] ?? false;

    $alreadySubmitted = json_decode(get_post_meta($formID, "submitted_forms", true), true);
    if (!$formID || !$submissionID || !remove_submission($alreadySubmitted, $submissionID)) {
        wp_send_json_error("Nebol nájdený záznam na vymazanie.");
    }

    if (update_post_meta($formID, 'submitted_forms', json_encode($alreadySubmitted))) {

        remove_directory(get_template_directory() . '/form_uploads/' . $formID . '/' . $submissionID);

        wp_send_json_success("Záznam s ID $submissionID bol vymazaný.");
    }
    wp_send_json_error("Zmazanie registrácie sa nepodarilo.");
}

function remove_submission(&$array, $targetId)
{
    foreach ($array as $index => $innerArray) {
        if (isset($innerArray['id']) && $innerArray['id'] == $targetId) {
            unset($array[$index]);
            return true;
        }
    }
    return false;
}


function hook_before_removal_of_form_post_type($post_id)
{
    if (get_post_type($post_id) === 'form') {
        remove_directory(get_template_directory() . '/form_uploads/' . $post_id);
    }
}

add_action('before_delete_post', 'hook_before_removal_of_form_post_type');


add_action('wp_ajax_export_submissions', 'export_submissions');
function export_submissions()
{
    require_once get_template_directory() . "/vendor/autoload.php";

    $form_id = $_GET['form_id'] ?? false;

    if (!$form_id) die("Nebolo zadané ID formuláru");

    // Get the submitted forms meta JSON
    $submitted_forms = json_decode(decodeUnicodeString(get_post_meta($form_id, 'submitted_forms', true)), true);

    if (!empty($submitted_forms)) {
        // Create a new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $headerRow = 1;
        $column = 1;

        foreach ($submitted_forms[0] as $key => $value) {
            // Set the header value in the spreadsheet
            $headerCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($column) . $headerRow;
            $sheet->setCellValue($headerCoordinate, $key);
            $column++;
        }

        // Set data rows
        $row = 2;

        foreach ($submitted_forms as $form) {
            $column = 1;

            foreach ($form as $value) {
                // Set the value in the spreadsheet
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($column) . $row;
                $sheet->setCellValue($cellCoordinate, is_array($value) ? implode(", ", $value) : $value);
                $column++;
            }

            $row++;
        }

        // Save the spreadsheet as a file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'form_data.xlsx';
        $writer->save($filename);

        // Download the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        readfile($filename);
        exit;
    }
}