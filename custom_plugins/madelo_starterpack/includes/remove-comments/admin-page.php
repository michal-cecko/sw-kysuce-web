<div class="wrap">
    <h1>Nastavenia komentárov</h1>
    <form action="<?= admin_url("options.php") ?>" method="post">
        <?php settings_fields('madelo_comments'); ?>
        <?php do_settings_sections('madelo_comments'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Vypnutie komentárov:</th>
                <td>
                    <select name="madelo_comments_status">
                        <?php $status = get_option('madelo_comments_status');?>
                        <option <?php if($status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="hidden" name="madelo_coment_admin_form" value="1">
        <?php submit_button(); ?>
    </form>
</div>