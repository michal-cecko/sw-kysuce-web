<div class="wrap">
    <h1>Under construction</h1>
    <form action="<?= admin_url("options.php") ?>" method="post">
        <?php settings_fields( 'madelo_under_construction' ); ?>
        <?php do_settings_sections( 'madelo_under_construction' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Stav</th>
                <td>
                    <select name="madelo_under_construction_status">
                        <?php $status = get_option('madelo_under_construction_status');?>
                        <option <?php if($status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Title</th>
                <?php $title = get_option('madelo_under_construction_title'); ?>
                <td><input type="text" style="width: 20rem;" name="madelo_under_construction_title" value="<?=$title?>"></td>
            </tr>
            <tr valign="top">
                <th scope="row">Description</th>
                <?php $desc = get_option('madelo_custom_scripts_description'); ?>
                <td><textarea style="min-width: 40rem; min-height: 10rem;" name="madelo_custom_scripts_description"><?=$desc?></textarea></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>