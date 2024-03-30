<div class="wrap">
    <h1>Custom Login page</h1>
    <form action="<?= admin_url("options.php") ?>" method="post">
        <?php settings_fields('madelo_custom_url'); ?>
        <?php do_settings_sections('madelo_custom_url'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Stav</th>
                <td>
                    <select name="madelo_custom_url_status">
                        <?php $url_status = get_option('madelo_custom_url_status');?>
                        <option <?php if($url_status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($url_status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Url slug</th>
                <?php $slug = esc_attr(get_option('madelo_custom_url_slug'));?>
                <td><input type="text" name="madelo_custom_url_slug" value="<?=$slug?>"></td>
            </tr>
            <tr valign="top">
                <th scope="row">Recaptcha Stav</th>
                <td>
                    <select name="madelo_custom_url_recaptcha_status">
                        <?php $url_status = get_option('madelo_custom_url_recaptcha_status');?>
                        <option <?php if($url_status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($url_status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Secret Key</th>
                <?php $secretKey = esc_attr(get_option('madelo_custom_url_recaptcha_secretKey')) ? : '' ?>
                <td><input style="width: 30rem;" type="text" name="madelo_custom_url_recaptcha_secretKey" value="<?=$secretKey?>"></td>
            </tr>
            <tr valign="top">
                <th scope="row">Site Key</th>
                <?php $siteKey = esc_attr(get_option('madelo_custom_url_recaptcha_siteKey')) ? : '' ?>
                <td><input style="width: 30rem;" type="text" name="madelo_custom_url_recaptcha_siteKey" value="<?= $siteKey ?>"></td>
            </tr>
        </table>
        <input type="hidden" name="madelo_custom_url_slug_reg" value="">
        <?php submit_button(); ?>
    </form>
</div>