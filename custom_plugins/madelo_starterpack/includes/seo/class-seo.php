<?php

$madeloSeo = new madeloSeo();

class madeloSeo
{
    public function __construct()
    {
        register_activation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'activate'));
        register_deactivation_hook(MADELO_PLUGIN_FILE_URL, array($this, 'deactivate'));
		
		if (get_option('madelo_seo_status') == 'zapnute')
		{
			add_action('add_meta_boxes', [$this, 'addMetaBox']);
			add_action('admin_enqueue_scripts', [$this, 'enqueJS']);
			add_action('save_post', [$this, 'savePost']);
            add_action('wp_head', [$this,'addCustomMeta']);
		}

        add_action('admin_menu', [$this, 'registerOptionPage']); //Pridanie option page do adminky
		add_action('added_option', [$this, 'saveSettings'], 10, 2); //Uloženie nových nastavení
        add_action('updated_option', [$this, 'saveSettings'], 10, 3); //Uloženie nových nastavení
        
    }

    public function activate()
    {
		$this->registerSettings();
    }

    public function deactivate()
    {

    }

    function registerOptionPage()
    {
        add_submenu_page(
            'madelo_plugin', //parent
            'Nastavenia SEO', // Názov stránky
            'Nastavenia SEO', // Názov v menu
            'manage_options', // Oprávnenie na zobrazenie stránky
            'madelo_seo_settings', // Identifikátor stránky
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
        register_setting('madelo_seo', 'madelo_seo_status');
		register_setting('madelo_seo', 'madelo_seo_postTypes');
    }

	
	public function addMetaBox( $postType ) 
	{
		$postTypes = get_option('madelo_seo_postTypes');
		$postTypes = $postTypes ? explode(',', $postTypes) : array();

		if (in_array($postType, $postTypes ))
        {
			add_meta_box(
				'seo_metabox',
				__('SEO Settings', 'textdomain'),
				array($this, 'renderMetaBox'),
				$postType,
				'advanced',
				'high'
			); 
		}
	}
	
	public function saveSettings()
	{
		if(isset($_POST['madelo_seo_admin_form']))
		{
			$postTypes = array();
			foreach($_POST as $key => $value)
			{
				$expl = explode('post_type_', $key);
				if(array_key_exists(1, $expl)) $postTypes[] = $expl[1];
			}
			$postTypes = implode(',',$postTypes);
			update_option('madelo_seo_postTypes', $postTypes);
		}
	}
	
	public function savePost($postID)
	{

        if(!isset($_POST['madeloCustomSeoNonce']) || !wp_verify_nonce($_POST['madeloCustomSeoNonce'], 'madeloCustomSeoNonce_nonce')) return;

		if(!isset($_POST['madeloSeo'])) return;
		
		$seoCustomImg = sanitize_text_field($_POST['seoCustomImg']);
		$seoCustomDesc = sanitize_text_field($_POST['seoCustomDesc']);
        $seoCustomTitle = sanitize_text_field($_POST['seoCustomTitle']);

        if(metadata_exists('post', $postID, 'seoCustomDesc'))
            update_post_meta($postID, 'seoCustomDesc', $seoCustomDesc);
        else
            add_metadata('post', $postID, 'seoCustomDesc', $seoCustomDesc);

        if(metadata_exists('post', $postID, 'seoCustomImg'))
            update_post_meta( $postID, 'seoCustomImg', $seoCustomImg );
        else
            add_metadata('post', $postID, 'seoCustomImg', $seoCustomImg);

        if(metadata_exists('post', $postID, 'seoCustomTitle'))
            update_post_meta( $postID, 'seoCustomTitle', $seoCustomTitle );
        else
            add_metadata('post', $postID, 'seoCustomTitle', $seoCustomTitle);
	}
	
	public static function ignorePostTypes()
	{
		return array(
			'attachment',
			'revision',
			'nav_menu_item',
			'custom_css',
			'customize_changeset',
			'oembed_cache',
			'user_request',
			'wp_block',
			'wp_template',
			'wp_template_part',
			'wp_global_styles',
			'wp_navigation',
            'acf-field-group',
            'acf-field'
		);
	}
	
	public function renderMetaBox( $post )
	{
		include('metabox-template.php');
	}
	
	public function enqueJS()
	{
		wp_enqueue_script('seo_javascript', plugin_dir_url( __FILE__ ) . 'js/seo.js', array('jquery'), NULL, false);
	}

    public function getAllowedPostTypes($postType)
    {
        $postTypes = get_option('madelo_seo_postTypes');
        $postTypes = $postTypes ? explode(',', $postTypes) : array();
        return in_array($postType, $postTypes);
    }


    public function addCustomMeta()
    {
        global $post;

        if($post && !$this->getAllowedPostTypes(get_post_type($post->ID))) return;


        $postDate = $post?->post_date;
        $type = is_single() ? 'article' : 'website';
        $seoCustomDesc = strip_tags(get_post_meta($post->ID, 'seoCustomDesc', true));
        $seoDefaultImg = get_template_directory_uri() . "/assets/images/default_og_image.jpg";
        $seoCustomImg = get_post_meta($post->ID, 'seoCustomImg', true);
        $seoTitle = get_post_meta($post->ID, 'seoCustomTitle', true);

        if(!$seoTitle)
        {
            $seoTitle = get_the_title($post->ID) ? : '';
        }

        if(!$seoCustomDesc)
        {
            $seoCustomDesc = substr(strip_tags(get_post_field('post_content', $post->ID)), 0, 150);
        }

        $desc = $seoCustomDesc ? : get_bloginfo('description');
        if(!empty($seoCustomImg))
        {
            $metadata = getimagesize($seoCustomImg);
            $width = $metadata[0];
            $height = $metadata[1];
        }
        else
        {
            $seoCustomImg = get_post_thumbnail_id($post->ID);
            if($seoCustomImg)
            {
                $metadata = wp_get_attachment_metadata($seoCustomImg);
                $width = $metadata['width'];
                $height = $metadata['height'];
                $seoCustomImg = wp_get_attachment_url($seoCustomImg);
            }
            else
            {
                $seoCustomImg = $seoDefaultImg;
                $metadata = getimagesize($seoCustomImg);
                $width = $metadata[0] ?? "";
                $height = $metadata[1] ?? "";
            }
        }
        ?>
        <!-- This site is optimized with the Madelo plugin -->
        <meta name="description" content="<?=$seoCustomDesc?>" />
        <link rel="canonical" href="<?= get_permalink() ?>" />
        <meta property="og:locale" content="<?=get_bloginfo('language')?>" />
        <meta property="og:type" content="<?=$type?>" />
        <?php
        if($seoTitle)
        {
            ?>
            <meta property="og:title" content="<?=$seoTitle?> - <?=get_bloginfo('name')?>" />
            <?php
        }
        ?>
        <meta property="og:description" content="<?=strip_tags($desc)?>" />
        <meta property="og:url" content="<?=get_permalink()?>" />
        <meta property="og:site_name" content="<?=get_bloginfo('name')?>" />
        <?php if ($postDate) : ?>
        <meta property="article:published_time" content="<?=$postDate?>" />
        <meta property="article:modified_time" content="<?=$postDate?>" />
    <?php endif ?>
        <?php
        if($seoCustomImg)
        {
            ?>
            <meta property="og:image" content="<?=$seoCustomImg?>" />
            <?php
        }
        if($width && $height)
        {
            ?>
            <meta property="og:image:width" content="<?=$width?>" />
            <meta property="og:image:height" content="<?=$height?>" />
            <?php
        }
        ?>
        <!-- End Madelo plugin -->
        <?php
    }

    public function stripWPText($content) {
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        $content = strip_tags($content);
        $content = substr($content, 0, 150);
        return $content;
    }
	
	public function __destruct()
    {
		
    }

}

