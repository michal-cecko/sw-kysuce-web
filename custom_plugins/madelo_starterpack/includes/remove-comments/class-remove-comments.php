<?php

$removeComments = new removeComments();

class removeComments
{
    public function __construct()
    {
        register_activation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'activate'));
        register_deactivation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'deactivate'));

        if (get_option('madelo_comments_status') == 'zapnute')
        {
            add_action('init',[$this,'redirect']);
            add_action('init',[$this,'removePostTypes']);
            add_action('init',[$this,'addFilters']);
            add_action('wp_before_admin_bar_render',[$this,'removeAdminBarAdmin']);
            add_action('admin_menu', [$this, 'removeAdminPage']);
            add_action('init', [$this, 'removeAdminBar']);
        }

        add_action('admin_menu', [$this, 'registerOptionPage']); //Pridanie option page do adminky
        add_action('added_option', [$this, 'saveSettings'], 10, 2); //Uloženie nových nastavení
        add_action('updated_option', [$this, 'saveSettings'], 10, 3); //Uloženie nových nastavení
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
            'Nastavenia komentárov', // Názov stránky
            'Nastavenia komentárov', // Názov v menu
            'manage_options', // Oprávnenie na zobrazenie stránky
            'madelo_custom_comments_settings', // Identifikátor stránky
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
        register_setting('madelo_comments', 'madelo_comments_status');
    }

    public function setDefaultOptions()
    {
        update_option('madelo_comments_status', 'zapnute');
    }

    public function saveSettings($postID)
    {
        if(isset($_POST['madelo_coment_admin_form']))
        {
            $status = $_POST['madelo_comments_status'];
            if (metadata_exists('post', $postID, 'madelo_comments_status'))
                update_post_meta($postID, 'madelo_comments_status', $status);
            else
                add_metadata('post', $postID, 'madelo_comments_status', $status);
        }
    }


    public function redirect()
    {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_redirect(admin_url());
            exit;
        }
    }

    public function removePostTypes()
    {
        foreach (get_post_types() as $post_type)
        {
            if (post_type_supports($post_type, 'comments'))
            {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function addFilters()
    {
        add_filter('show_admin_bar', '__return_false');
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        add_filter('comments_array', '__return_empty_array', 10, 2);
    }

    public function removeAdminPage()
    {
        remove_menu_page('edit-comments.php');
    }

    public function removeAdminBarAdmin()
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }

    public function removeAdminBar()
    {
        if(is_admin_bar_showing())
        {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    }

    public function __destruct()
    {

    }

}

