// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.dish', {
		// creates control instances based on the control's id.
		// our button's id is "dish_button"
		createControl : function(id, controlManager) {
			if (id == 'dish_button') {
				// creates the button
				var button = controlManager.createButton('dish_button', {
					title : 'Dish Shortcode', // title of the button
					image : userSettings['url'] + 'wp-content/plugins/os-restaurant-menu/icon-dish.gif',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Dish Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mygallery-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin.
	tinymce.PluginManager.add('dish', tinymce.plugins.dish);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		var form = jQuery('<div id="mygallery-form"><table id="mygallery-table" class="form-table">\
			<tr>\
				<th><label for="dish">Select Dish: </label></th>\
				<td id="dish_select"></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="dish-submit" class="button-primary" value="Insert Dish" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// get dishes list and append it form
		jQuery.post(ajaxurl, {action: 'get_dishes_list'}, function(resp)
			{
				jQuery('#dish_select').append(resp);
			});
		
		// handles the click event of the submit button
		form.find('#dish-submit').click(function(){
			var dish_id = jQuery('#dish').val();
			var dish_title = jQuery('#dish option:selected').attr('title');
			var shortcode = '[dish dish_id="' + dish_id + '" title="' + dish_title + '"]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()