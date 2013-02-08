
/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   language_fields 
 * @author    Daniel Kiesel 
 * @license   LGPL 
 * @copyright Daniel Kiesel 2013 
 */


/**
 * Languagefields functionality
 */
window.addEvent('domready', function() {
	
	// User clicks on page
	$(document.body).addEvent('click', function(el) {
		
		var obj   = $(el.target);
		var field = obj.getParent('.lf_field_wrap');
		
		if (obj == null)
		{
			return;
		}
		
		// User clicked on the flag button
		if (obj.hasClass('lf_button') || obj.getParent('.lf_button') != null)
		{
			// Open language list
			field.getElement('.lf_lng_list').toggleClass('active');
		}
		else
		{
			// User clicked on a language list item
			if (obj.hasClass('lf_lng_item') || obj.getParent('.lf_lng_item') != null)
			{
				var listItem = obj.getParent('.lf_lng_item');
				
				if (listItem == null && obj.hasClass('lf_lng_item'))
				{
					listItem = obj;
				}
				
				// Get language from chosen item
				var language = listItem.get('id').replace('lng_name_', '');
				
				// Change the langauge button
				field.getChildren('.lf_button').each(function(el) {
					var img = el.getElement('img');
					var src = img.get('src');
					
					// Generate new path
					src = (src.substr(0, src.length - 6) + language + src.substr(src.length - 4, src.length));
					
					// Set new path
					img.set('src', src);
				});
				
				// Show the requested language field and hide the others
				field.getChildren('.lf_lng_field').each(function(el) {
					el.addClass('hide');
					
					if (el.get('id') == 'ctrl_name_' + language)
					{
						el.removeClass('hide');
					}
				});
			}
			// ELSE User clicked on the page
			
			$$('.lf_field_wrap').getElement('.lf_lng_list').removeClass('active');
		}
	});
	
	
	// User translates fields
	$$('.lf_field_wrap').getChildren('.lf_lng_field').each(function() {
		$(this).addEvent('keyup', function(el) {
			
			var obj = $(el.target);
			
			// Get active language from field
			var language = obj.get('id').replace('ctrl_name_', '');
			
			// Get list item from active language
			var listItem = obj.getParent('.lf_field_wrap').getElement('.lf_lng_list').getElement('#lng_name_' + language);
			
			// Set or unset translated class on active list item
			if (obj.get('value').length > 0)
			{
				listItem.addClass('translated');
			}
			else
			{
				listItem.removeClass('translated');
			}
		});
	});
});
