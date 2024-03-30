<div class="wrap">
    <h1>Nastavenia SEO</h1>
    <form action="<?= admin_url("options.php") ?>" method="post">
        <?php settings_fields('madelo_seo'); ?>
        <?php do_settings_sections('madelo_seo'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Stav</th>
                <td>
                    <select name="madelo_seo_status">
                        <?php $status = get_option('madelo_seo_status');?>
                        <option <?php if($status === 'vypnute') echo 'selected';?> value="vypnute">Vypnuté</option>
                        <option <?php if($status === 'zapnute') echo 'selected';?> value="zapnute">Zapnuté</option>
                    </select>
                </td>
            </tr>
			<tr>
				<td><h2>Post types</h2></td>
			</tr>
			<?php
				$ignoredPostTypes = madeloSeo::ignorePostTypes();
				
				$postTypes = get_option('madelo_seo_postTypes');
				$postTypes = $postTypes ? explode(',', $postTypes) : array();
				
				foreach(get_post_types() as $postType)
				{
					if(in_array($postType, $ignoredPostTypes)) continue;
					$checked = in_array($postType, $postTypes) ? 'checked' : '';
					?>
					<tr>
						<td><?=$postType?></td>
						<td><input type="checkbox" <?=$checked?> name="post_type_<?=$postType?>"></td>
					</tr>
					<?php
				}
				?>
        </table>
		<input type="hidden" name="madelo_seo_admin_form" value="1">
        <?php submit_button(); ?>
    </form>
</div>