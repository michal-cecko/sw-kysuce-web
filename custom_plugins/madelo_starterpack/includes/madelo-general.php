<?php

    class madeloGeneral{
        public function __construct()
        {
            add_action('admin_menu', [$this, 'registerMenu']);
            $this->loadParts();
        }

        public function registerMenu()
        {
            add_menu_page(
                'Madelo Plugin', // Názov stránky
                'Madelo Plugin', // Názov v menu
                'manage_options', // Oprávnenie na zobrazenie stránky
                'madelo_plugin', // Identifikátor stránky
                [$this, 'getPluginPage'], // Funkcia, ktorá vytvorí obsah stránky
                'dashicons-admin-plugins', // Ikona pre položku menu
                1 // Pozícia položky v menu
            );
        }

        public function loadParts()
        {
            include_once('custom-login/class-custom-login.php');
			include_once('seo/class-seo.php');
            include_once('remove-comments/class-remove-comments.php');
            include_once('custom-scripts/class-custom-scripts.php');
            include_once('under-construction/class-under-construction.php');
        }

        public function getPluginPage()
        {
            include('admin-page/admin-page.php');
        }

        public function __destruct()
        {

        }
    }