<?php

$madeloUnderConstruction = new madeloUnderConstruction();

class madeloUnderConstruction
{

    public function __construct()
    {
        register_activation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'activate'));
        register_deactivation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'deactivate'));

        if (get_option('madelo_under_construction_status') == 'zapnute')
        {
            add_action('template_redirect', [$this, 'redirectCustomPage']);

            add_action('admin_head', function(){
                echo '<style>#wp-admin-bar-madelo-under-construction a{color: red!important; font-weight: bold;}</style>';
            });

            add_action('admin_bar_menu', [$this,'addToAdminBar'], 100);
        }

        add_action('admin_menu', [$this, 'registerOptionPage']); //Pridanie option page do adminky
    }

    public function activate()
    {
        $this->registerSettings();
        $this->setDefaultOptions();
    }

    public function deactivate()
    {

    }

    function registerOptionPage()
    {
        add_submenu_page(
            'madelo_plugin', //parent
            'Under construction', // Názov stránky
            'Under construction', // Názov v menu
            'manage_options', // Oprávnenie na zobrazenie stránky
            'madelo_under_construction_settings', // Identifikátor stránky
            [$this, 'getAdminPage'] // Funkcia, ktorá vytvorí obsah stránky
        );
        add_action('admin_init', [$this, 'registerSettings']); //definovanie custom options hodnôt
    }

    public function getAdminPage()
    {
        include('admin-page.php');
    }

    public function registerSettings()
    {
        register_setting('madelo_under_construction', 'madelo_under_construction_status');
        register_setting('madelo_under_construction', 'madelo_under_construction_title');
        register_setting('madelo_under_construction', 'madelo_custom_scripts_description');
    }

    public function setDefaultOptions()
    {
        update_option('madelo_under_construction_status', 'vypnute');
        update_option('madelo_under_construction_title', 'Aktuálne prebieha údržba.');
        update_option('madelo_custom_scripts_description', 'Ospravedlňujeme sa. Aktuálne na tejto stránke prebieha údržba.');
    }

    public function redirectCustomPage()
    {
        $current_user = wp_get_current_user();
        if(
            !user_can( $current_user, 'administrator' ) && (
            (!get_query_var('prihlasenie') && get_option('madelo_custom_url_status') != 'zapnute') ||
            $GLOBALS['pagenow'] !== 'wp-login.php' ||
            $GLOBALS['pagenow'] !== 'wp-admin.php')
        )
        {
            include('under-construction-template.php');
            exit;
        }
        else if($GLOBALS['pagenow'] !== 'wp-login.php' || $GLOBALS['pagenow'] !== 'wp-admin.php')
        {
            add_action('wp_head', [$this,'addAdminNotification']);
        }
    }

    public function addAdminNotification()
    {
        include('admin-notification.php');
    }

    public function addToAdminBar($adminBar)
    {
        $adminBar->add_menu( array(
            'id'    => 'madelo-under-construction',
            'title' => 'Under construction',
            'href'  => admin_url("admin.php?page=madelo_under_construction_settings")
        ));
    }

    public function __destruct()
    {

    }

}

