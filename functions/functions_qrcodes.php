<?php

function generate_pay_by_square_qr($iban, $amount, $note, $vs = "")
{
    $price = floatval($amount);
    $note = strtolower(custom_remove_accents($note));
    $cs = "";//leave empty if not needed
    $ss = "";//leave empty if not needed
    $swift = "TATRSKBX";
    $date = date("Ymd");

    ob_start();
    require_once '../wp-content/themes/' . get_stylesheet() . '/qrcodes/qr.php';
    return ob_get_clean();
}