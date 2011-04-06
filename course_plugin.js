// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.course', {
		// creates control instances based on the control's id.
		// our button's id is "dish_button"
		createControl : function(id, controlManager) {
			if (id == 'course_button') {
				// creates the button
				var button = controlManager.createButton('course_button', {
					title : 'Course Shortcode', // title of the button
					image : userSettings['url'] + 'wp-content/plugins/food-menu-plugin/icon-course.gif',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Course Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=course-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin.
	tinymce.PluginManager.add('course', tinymce.plugins.course);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		var course_form = jQuery('<div id="course-form"><table id="course-table" class="form-table">\
			<tr>\
				<th><label for="course">Select Course: </label></th>\
				<td id="course_select"></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="course-submit" class="button-primary" value="Insert Course" name="submit" />\
		</p>\
		</div>');
		
		var table = course_form.find('table');
		course_form.appendTo('body').hide();
		
		// get dishes list and append it form
		jQuery.post(ajaxurl, {action: 'get_courses_list'}, function(resp)
			{
				jQuery('#course_select').append(resp);
			});
		
		// handles the click event of the submit button
		course_form.find('#course-submit').click(function(){
			var course_id = jQuery('#course').val();
			var course_title = jQuery('#course option:selected').attr('title');
			var shortcode = '[course course_id="' + course_id + '" title="' + course_title + '"]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()