<?php

//currency: EUR, CZK..., due date in Y-m-d
function generate_pay_by_square_qr($iban, $amount, $currency, $dueDate, $bic, $note, $submissionID, $formID)
{
    $price = floatval($amount);
    $note = strtolower(custom_remove_accents($note));

    // Constructing the URL with parameters
    $url = 'https://services.profit365.eu/API/QR/PayBySquare.png?' . http_build_query([
            'IBAN' => str_replace(" ", "", $iban),
            'Currency' => $currency, // Assuming EUR as currency
            'Amount' => $price,
            'Bic' => $bic,
            'DueDate' => $dueDate,
            'Description' => $note, // URL encode the note
            'Size' => 256 // Assuming size parameter
        ]);

    // Making HTTP request
    $qr_code_data = file_get_contents($url);

    // Check if the request was successful
    if ($qr_code_data === false) {
        return false; // Failed to fetch QR code data
    }

    // Create directory for the submission
    $submissionDirectoryDir = wp_upload_dir()['basedir'] . '/form_uploads/' . $formID . '/' . $submissionID;
    $submissionDirectoryUrl = wp_upload_dir()['baseurl'] . '/form_uploads/' . $formID . '/' . $submissionID;
    if (!wp_mkdir_p($submissionDirectoryDir)) {
        return false; // Unable to create directory, handle accordingly
    }

    // Generate a unique filename for the GIF file
    $filename = 'pay_by_square.gif';

    // Concatenate the directory path and the filename
    $file_path = $submissionDirectoryDir . '/' . $filename;
    $file_url = $submissionDirectoryUrl . '/' . $filename;

    // Save the QR code data to a file
    if (file_put_contents($file_path, $qr_code_data) === false) {
        return false; // Failed to save QR code image
    }

    // Return the path to the saved GIF file
    return $file_url;
}