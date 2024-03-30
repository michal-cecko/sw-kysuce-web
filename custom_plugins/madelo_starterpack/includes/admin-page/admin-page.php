<style>
    .madeloWelcome{
        margin-top: 2rem;
        font-size: 12pt;
        max-width: 80%;
    }
    h1{
        margin-top: 3rem;
    }
    table{
        margin-top: 2rem;
    }
    table td{
        padding: 0 0 1.5rem 1.5rem;
    }
    .madeloService{
        font-size: 13pt;
        font-weight: bold;
    }
    .madeloSubService{
        font-size: 10pt;
        font-weight: bold;
    }
    .madeloON{
        color: #006700;
    }
    .madeloOFF{
        color: #c20101;
    }
</style>
<div class="container">
    <h1>Madelo Plugin</h1>
    <p class="madeloWelcome">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

    <table>
        <tr>
            <?php
            if (get_option('madelo_custom_url_status') == 'zapnute')
            {
                $class="madeloON"; $txt = 'Zapnuté';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloService">Custom login page:</span></td>
            <td><span class="madeloService <?=$class?>"><?=$txt?></span></td>
        </tr>
        <tr>
            <td><span class="madeloSubService">URL Slug:</span></td>
            <td><span class="madeloSubService"><?=sanitize_text_field(get_option('madelo_custom_url_slug'))?></span></td>
        </tr>
        <tr>
            <?php
            if (get_option('madelo_custom_url_recaptcha_status') == 'zapnute')
            {
                $class="madeloON"; $txt = 'Zapnuté';
                if (get_option('madelo_custom_url_status') != "zapnute") $class = 'madeloOFF';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloSubService">Recaptcha:</span></td>
            <td><span class="madeloSubService <?=$class?>"><?=$txt?></span></td>
        </tr>
    </table>

    <table>
        <tr>
            <?php
            if (get_option('madelo_seo_status') == 'zapnute')
            {
                $class="madeloON"; $txt = 'Zapnuté';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloService">Custom SEO:</span></td>
            <td><span class="madeloService <?=$class?>"><?=$txt?></span></td>
        </tr>
        <tr>
            <td><span class="madeloSubService">Post types:</span></td>
            <td>

                <?php
                $ignoredPostTypes = madeloSeo::ignorePostTypes();

                $postTypes = get_option('madelo_seo_postTypes');
                $postTypes = $postTypes ? explode(',', $postTypes) : array();
                $all = get_post_types();
                foreach($all as $key => $postType)
                {
                    if(in_array($postType, $ignoredPostTypes)) continue;
                    $class = in_array($postType, $postTypes) ? 'madeloON' : 'madeloOFF';
                    ?>
                        <span class="madeloSubService <?=$class?>"><?=$postType?></span>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <?php
            if (get_option('madelo_comments_status') == 'zapnute')
            {
                $class="madeloON"; $txt = 'Zapnuté';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloService">Vypnutie komentárov:</span></td>
            <td><span class="madeloService <?=$class?>"><?=$txt?></span></td>
        </tr>
    </table>
    <table>
        <tr>
            <?php
            if (get_option('madelo_custom_scripts_status') == 'zapnute')
            {
                $class="madeloON"; $txt = 'Zapnuté';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloService">Custom scripts:</span></td>
            <td><span class="madeloService <?=$class?>"><?=$txt?></span></td>
        </tr>
        <tr>
            <?php
            if (get_option('madelo_custom_scripts_head'))
            {
                $class="madeloON"; $txt = 'Vyplnené';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Prázdne';
            }
            ?>
            <td><span class="madeloSubService">Head:</span></td>
            <td><span class="madeloSubService <?=$class?>"><?=$txt?></span></td>
        </tr>
        <tr>
            <?php
            if (get_option('madelo_custom_scripts_body'))
            {
                $class="madeloON"; $txt = 'Vyplnené';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Prázdne';
            }
            ?>
            <td><span class="madeloSubService">Body:</span></td>
            <td><span class="madeloSubService <?=$class?>"><?=$txt?></span></td>
        </tr>
        <tr>
            <?php
            if (get_option('madelo_custom_scripts_footer'))
            {
                $class="madeloON"; $txt = 'Vyplnené';
            }
            else
            {
                $class="madeloOFF"; $txt = 'Prázdne';
            }
            ?>
            <td><span class="madeloSubService">Footer:</span></td>
            <td><span class="madeloSubService <?=$class?>"><?=$txt?></span></td>
        </tr>
    </table>
    <table>
        <tr>
            <?php
            if (get_option('madelo_under_construction_status') == 'zapnute')
            {
                $class="madeloOFF"; $txt = 'Zapnuté';
            }
            else
            {
                $class="madeloON"; $txt = 'Vypnuté';
            }
            ?>
            <td><span class="madeloService">Under construction: </span></td>
            <td><span class="madeloService <?=$class?>"><?=$txt?></span></td>
        </tr>
    </table>
</div>