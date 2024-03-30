<?php

$customLogin = new customLogin();

class customLogin
{
    public function __construct()
    {
        register_activation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'activate'));
        register_deactivation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'deactivate'));

        if (get_option('madelo_custom_url_status') == 'zapnute')
		{
            add_action('login_init', [$this, 'setErr']); //404 pri otvoreni wp-login.php
            add_filter('logout_url', [$this, 'custom_logout_url'], 10, 2); //presmerovanie logout linku na custom page
            add_action('template_redirect', [$this, 'login_template']); //include login templaty
            add_filter('query_vars', [$this, 'add_query_var']); //pridanie query_var pre link custom page
            add_action( 'init', [$this, 'setPermalinks']); // registrovanie linkov
        }

        add_action('admin_menu', [$this, 'registerOptionPage']); //Pridanie option page do adminky
        add_action('added_option', [$this, 'saveSettings'], 10, 2); //Uloženie nových nastavení
        add_action('updated_option', [$this, 'saveSettings'], 10, 3); //Uloženie nových nastavení
    }

    public function activate()
    {
		$this->registerURLsettings();
		$this->setDefaultOptions();
    }

    public function deactivate()
    {

    }

    function registerOptionPage()
    {
        add_submenu_page(
            'madelo_plugin', //parent
            'Nastavenia login URL', // Názov stránky
            'Nastavenia login URL', // Názov v menu
            'manage_options', // Oprávnenie na zobrazenie stránky
            'madelo_custom_url_settings', // Identifikátor stránky
            [$this, 'getAdminPage'] // Funkcia, ktorá vytvorí obsah stránky
        );
        add_action('admin_init', [$this, 'registerURLsettings']); //definovanie custom options hodnôt
    }

    public function setPermalinks()
    {
        $slugReg = sanitize_text_field(get_option('madelo_custom_url_slug_reg'));
		$slug = sanitize_text_field(get_option('madelo_custom_url_slug'));
		$url = '^' . $slug . '/?$';
        add_rewrite_rule($url, 'index.php?prihlasenie=true', 'top');
        if($slugReg != 'off'){
			global $wp_rewrite;
            $wp_rewrite->set_permalink_structure('/%postname%/');
            update_option("rewrite_rules", false);
            $wp_rewrite->flush_rules(true);
            update_option('madelo_custom_url_slug_reg', 'off');
        }
    }

    public function setErr()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        exit;
    }

    public function custom_logout_url()
    {
        return home_url('/' . self::getURL() . '/?logout=true');
    }

    public function login_template()
    {
        if (get_query_var('prihlasenie')) {
            include('custom-login-template.php');
            exit;
        }
    }

    public function add_query_var($vars)
    {
        $vars[] = 'prihlasenie';
        return $vars;
    }

    public static function getURL($full = false)
    {
        if($full)
        {
            if(get_option('madelo_custom_url_status') == 'zapnute')
                return home_url('/' . sanitize_text_field(get_option('madelo_custom_url_slug')) . '/');

            return false;
        }

        return sanitize_text_field(get_option('madelo_custom_url_slug'));
    }

    public function getAdminPage()
    {
        include('admin-page.php');
    }

    public function registerURLsettings()
    {
        register_setting('madelo_custom_url', 'madelo_custom_url_status');
        register_setting('madelo_custom_url', 'madelo_custom_url_slug');
        register_setting('madelo_custom_url', 'madelo_custom_url_slug_reg');
        register_setting('madelo_custom_url', 'madelo_custom_url_recaptcha_status');
        register_setting('madelo_custom_url', 'madelo_custom_url_recaptcha_secretKey');
        register_setting('madelo_custom_url', 'madelo_custom_url_recaptcha_siteKey');
    }
	
	public function setDefaultOptions()
	{
		update_option('madelo_custom_url_slug_reg', 'vypnute');
        update_option('madelo_custom_url_recaptcha_status', 'vypnute');
	}

    public function saveSettings()
    {
        if (isset($_POST['madelo_custom_url_status']))
        {
            $slug = sanitize_text_field(get_option('madelo_custom_url_slug'));

            if ($_POST["madelo_custom_url_status"] == 'zapnute' && $slug)
            {
                update_option('madelo_custom_url_status', 'zapnute');
                update_option('madelo_custom_url_slug_reg', $slug);
            }
            else
            {
                update_option('madelo_custom_url_status', 'vypnute');
            }

            $secretKey = $_POST['madelo_custom_url_recaptcha_secretKey'];
            $siteKey = $_POST['madelo_custom_url_recaptcha_siteKey'];

            if ($_POST["madelo_custom_url_recaptcha_status"] == 'zapnute' && $secretKey && $siteKey)
            {
                update_option('madelo_custom_url_recaptcha_status', 'zapnute');
            }
            else
            {
                update_option('madelo_custom_url_recaptcha_status', 'vypnute');
            }
        }
    }

    public static function checkLogin($post)
    {

        if (!isset($post['btn_submit']))
            return '';

        $username = sanitize_text_field($post['username']);
        $password = sanitize_text_field($post['password']);

        if(get_option('madelo_custom_url_recaptcha_status') != 'vypnute')
        {
            $token = $_POST['token'];
            if(!$token) return 'Nastala chyba, skúste znovu!';
            if(!self::checkCaptcha($token)) return 'Nastala chyba, skúste znovu!';
        }

        if ($username == "" || $password == "")
            return ('Musíte vyplniť všetky polia!');

        $user_data = array();
        $user_data['user_login'] = $username;
        $user_data['user_password'] = $password;
        $user_data['remember'] = true;
        $user = wp_signon($user_data, false);

        if (is_wp_error($user))
            return ('Nesprávne uživateľské meno / heslo');

        wp_set_current_user($user->ID, $username);
        do_action('set_current_user');
        wp_redirect(admin_url());
    }

    public static function checkCaptcha($response)
    {
        if(get_option('madelo_custom_url_recaptcha_status') === 'vypnute')
            return true;


        $secretKey = get_option('madelo_custom_url_recaptcha_secretKey');
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = array(
            'secret' => $secretKey,
            'response' => $response
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($recaptchaData)
            )
        );
        $context = stream_context_create($options);
        $verify = file_get_contents($recaptchaUrl, false, $context);
        return json_decode($verify)->success;
    }

    public static function includeCaptcha()
    {
        if(get_option('madelo_custom_url_recaptcha_status') === 'vypnute' || !get_option('madelo_custom_url_recaptcha_secretKey') || !get_option('madelo_custom_url_recaptcha_siteKey'))
            return '';

        $key = get_option('madelo_custom_url_recaptcha_siteKey');
        ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?=$key?>"></script>
        <script>
            $('#customLogin').submit(function(event) {
                event.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('<?=$key?>', {action: 'madelo_login'}).then(function(token) {
                        $('#customLogin').prepend('<input type="hidden" name="token" value="' + token + '">');
                        $('#customLogin').prepend('<input type="hidden" name="action" value="madelo_login">');
                        $('#customLogin').prepend('<input type="hidden" name="btn_submit" value="madelo_login">');
                        $('#customLogin').unbind('submit').submit();
                    });;
                });
            });
        </script>
        <?php
    }
	
	public function __destruct()
    {

    }

}

