<?php

    use _mailchimp_api\MailChimp;

    function echo_message( $type = 'success', $message = '', $internal_message = '' )
    {
        $message = [ 'type' => $type, 'message' => $message, 'internal_message' => $internal_message ];
        echo json_encode( $message );
    }

    add_action( "wp_ajax_newsletter_signup", "newsletter_signup" );
    add_action( "wp_ajax_nopriv_newsletter_signup", "newsletter_signup" );

    //mailchimp
    function newsletter_signup()
    {
        $email = isset( $_POST[ 'nt_data' ][ 'email' ] ) ? $_POST[ 'nt_data' ][ 'email' ] : FALSE;
        if ( $email ) {
            if ( !add_to_newsletter( $_POST[ 'nt_data' ][ 'email' ] ) ) {
                echo_message( 'danger', 'Táto emailová adresa už odoberá naše novinky.' );
            } else {
                echo_message( 'success', 'Ďakujeme, prihlásenie prebehlo úspešne.' );
            }
        } else {
            echo_message( 'danger', 'Prihlásenie neprebehlo úpsešne. Skúste neskôr.' );
        }

        die();
        exit();
    }

    function add_to_newsletter($email)
    {
        require_once( get_stylesheet_directory() .'/_mailchimp_api/MailChimp.php');

    $MailChimp = new MailChimp('zadaj'); //API Key
    $list_id = 'zadaj';

    $resultMailchimp = $MailChimp->post("lists/$list_id/members", [
        'email_address' => $email,
        'status' => 'pending',
    ]);

    if ($MailChimp->success()) {
        return true;
    } else {
        return false;
    }
}
