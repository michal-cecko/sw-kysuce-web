 jQuery(document).ready(function($){
		 
     $('#seo_custom_image_button').click(function(e) {
         e.preventDefault();
         var image = wp.media({
             title: 'Vybrať obrázok',
             multiple: false
         }).open().on('select', function(e){
             var uploaded_image = image.state().get('selection').first();
             var image_url = uploaded_image.toJSON().url;
             show_image_preview(image_url);
         });
     });

     $('#seo_custom_image_remove_button').click(function(e) {
         e.preventDefault();
         $('#seo_custom_thumbnail_preview').html('');
         $('#seo_custom_thumbnail').val('');
         $('#seo_custom_image_button').show();
         $('#seo_custom_image_remove_button').hide();
     });

     function show_image_preview(image_url) {
         $('#seo_custom_thumbnail_preview').html('<img src="' + image_url + '" style="max-width:20rem;cursor:pointer;">');
         $('#seo_custom_image_button').hide();
         $('#seo_custom_image_remove_button').show();
         $('#seo_custom_thumbnail').val(image_url);
         $('#seo_custom_thumbnail_preview img').click(function(e) {
             e.preventDefault();
             var image = wp.media({
                 title: 'Upraviť obrázok',
                 multiple: false
             }).open().on('select', function(e){
                 var uploaded_image = image.state().get('selection').first();
                 var image_url = uploaded_image.toJSON().url;
                 $('#seo_custom_thumbnail').val(image_url);
                 show_image_preview(image_url);
             });
         });
     }
		
     if (!$('#seo_custom_thumbnail').val()) {
         $('#seo_custom_image_remove_button').hide();
     }
     if($('#seo_custom_thumbnail').val()){
         $('#seo_custom_image_button').hide();
     }
});