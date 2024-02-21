<?php

function generate_pay_by_square($amount, $note)
{
    $price = $amount;
    $iban = get_field("transparent_bank_acc_iban", "options");
    $note = strtolower(custom_remove_accents($note));
    $vs = "";//leave empty if not needed
    $cs = "";//leave empty if not needed
    $ss = "";//leave empty if not needed
    $swift = "TATRSKBX";
    $date = date("Ymd");

    ob_start();
    require_once '../wp-content/themes/' . get_stylesheet() . '/qrcodes/qr.php';
    return ob_get_clean();
}