<?php

$madeloCustomScripts = new madeloCustomScripts();

class madeloCustomScripts
{
    public function __construct()
    {
        register_activation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'activate'));
        register_deactivation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'deactivate'));

        if (get_option('madelo_custom_scripts_status') == 'zapnute')
        {
            ob_start();
            add_action('init', function(){
                ob_start();
            });
            add_action('shutdown', [$this, 'updateOutput'],0);
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
            'Custom scripts', // Názov stránky
            'Custom scripts', // Názov v menu
            'manage_options', // Oprávnenie na zobrazenie stránky
            'madelo_seo_custom_scripts', // Identifikátor stránky
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
        register_setting('madelo_custom_scripts', 'madelo_custom_scripts_status');
        register_setting('madelo_custom_scripts', 'madelo_custom_scripts_head');
        register_setting('madelo_custom_scripts', 'madelo_custom_scripts_body');
        register_setting('madelo_custom_scripts', 'madelo_custom_scripts_footer');
    }

    public function setDefaultOptions()
    {
        update_option('madelo_custom_scripts_status', 'vypnute');
    }

    public function updateOutput()
    {
        $final = '';
        $levels = ob_get_level();

        for ($i = 0; $i < $levels; $i++) {
            $final .= ob_get_clean();
        }

        $return = $this->addScriptHead($final);
        $return = $this->addScriptBody($return);
        $return = $this->addScriptFooter($return);
        echo $return;
    }

    public function addScriptHead($content)
    {
        $headContent = get_option('madelo_custom_scripts_head');
        if(!$headContent) return $content;

        return str_replace('</head>', $headContent . '</head>', $content);
    }

    public function addScriptBody($content)
    {
        $bodyContent = get_option('madelo_custom_scripts_body');
        if(!$bodyContent) return $content;

        $body = '';
        $classes = get_body_class();
        if($classes)
        {
            $body = ' class="' . implode(' ', $classes) . '"';
        }
        $return = str_replace('<body' . $body . '>', '<body' . $body . '>' . $bodyContent, $content);
        return str_replace('<body>', '<body>' . $bodyContent, $return);
    }

    public function addScriptFooter($content)
    {
        $footerContent = get_option('madelo_custom_scripts_footer');
        if(!$footerContent) return $content;

        return str_replace('</body>', $footerContent . '</body>', $content);
    }

    public function __destruct()
    {

    }

}

