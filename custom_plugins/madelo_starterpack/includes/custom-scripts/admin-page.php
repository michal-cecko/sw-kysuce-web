<div class="wrap">
    <h1>Custom scripts</h1>
    <form action="<?= admin_url("options.php") ?>" method="post">
        <?php settings_fields('madelo_custom_scripts'); ?>
        <?php do_settings_sections('madelo_custom_scripts'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Stav</th>
                <td>
                    <select name="madelo_custom_scripts_status">
                        <?php $status = get_option('madelo_custom_scripts_status');?>
                        <option <?php if($status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Head</th>
                <?php $head = get_option('madelo_custom_scripts_head'); ?>
                <td><textarea style="min-width: 40rem; min-height: 10rem;" name="madelo_custom_scripts_head"><?=$head?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row">Body</th>
                <?php $body = get_option('madelo_custom_scripts_body'); ?>
                <td><textarea style="min-width: 40rem; min-height: 10rem;" name="madelo_custom_scripts_body"><?=$body?></textarea></td>
            </tr>
            <tr valign="top">
                <th scope="row">Footer</th>
                <?php $footer = get_option('madelo_custom_scripts_footer'); ?>
                <td><textarea style="min-width: 40rem; min-height: 10rem;" name="madelo_custom_scripts_footer"><?=$footer?></textarea></td>
            </tr>
        </table>
        <input type="hidden" name="madelo_custom_scripts_admin_form" value="1">
        <?php submit_button(); ?>
    </form>
</div>