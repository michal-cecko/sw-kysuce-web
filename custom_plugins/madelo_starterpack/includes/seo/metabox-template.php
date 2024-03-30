<div class="wrap">
	<table class="form-table">
		<tr>
			<?php
                wp_nonce_field('madeloCustomSeoNonce_nonce', 'madeloCustomSeoNonce');
			    global $post;
                $seoCustomDesc = get_post_meta($post->ID, 'seoCustomDesc', true);
            ?>
            <td><b>SEO Description:</b></td>
            <td><textarea name="seoCustomDesc" style="width: 80%;min-height: 10rem;" type="text" ><?=$seoCustomDesc?></textarea></td>
		</tr>
        <tr>
            <?php $seoCustomTitle = get_post_meta($post->ID, 'seoCustomTitle', true);?>
            <td><b>SEO Title:</b></td>
            <td><input name="seoCustomTitle" style="width: 80%;" type="text" value="<?=$seoCustomTitle?>"></td>
        </tr>
		<tr>
            <td><b>OG image:</b></td>
			<td>
				<?php $seoCustomImg = get_post_meta($post->ID, 'seoCustomImg', true);?>
				<input type="hidden" name="seoCustomImg" id="seo_custom_thumbnail" value="<?=$seoCustomImg?>">
				<button id="seo_custom_image_button" class="button">Vybrať obrázok</button>
				<div id="seo_custom_thumbnail_preview">
				<?php 
					if(!empty($seoCustomImg))
					{
				?>
					<img src="<?=esc_url($seoCustomImg);?>" style="cursor:pointer;max-width:20rem;height:auto;">
				<?php 
					} 
				?>
				</div>
				<button id="seo_custom_image_remove_button" style="margin-top: 10px;" class="button">Odstrániť obrázok</button> 
			</td>
		</tr>
	</table>
	<input type="hidden" name="madeloSeo" value="1">
</div>