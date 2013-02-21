
/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   translation_fields 
 * @author    Daniel Kiesel 
 * @license   LGPL 
 * @copyright Daniel Kiesel 2013 
 */


/**
 * TranslationFields functionality
 */
window.addEvent('domready', function() {
	
	// User clicks on page
	$(document.body).addEvent('click', function(el) {
		
		var obj       = $(el.target);
		var field     = obj.getParent('.tf_field_wrap');
		var fieldname = field.get('id').replace('ctrl_', '');
		
		if (obj == null)
		{
			return;
		}
		
		// User clicked on the flag button
		if (obj.hasClass('tf_button') || obj.getParent('.tf_button') != null)
		{
			// Open language list
			field.getElement('.tf_lng_list').toggleClass('active');
		}
		else
		{
			// User clicked on a language list item
			if (obj.hasClass('tf_lng_item') || obj.getParent('.tf_lng_item') != null)
			{
				var listItem = obj.getParent('.tf_lng_item');
				
				if (listItem == null && obj.hasClass('tf_lng_item'))
				{
					listItem = obj;
				}
				
				// Get language from chosen item
				var language = listItem.get('id').replace('lng_list_item_', '');
				
				// Change the langauge button
				field.getChildren('.tf_button').each(function(el) {
					var img = el.getElement('img');
					var src = img.get('src');
					
					// Generate new path
					src = (src.substr(0, src.length - 6) + language + src.substr(src.length - 4, src.length));
					
					// Set new path
					img.set('src', src);
				});
				
				// Show the requested language field and hide the others
				field.getChildren('.tf_lng_field').each(function(el) {
					el.addClass('hide');
					
					if (el.get('id') == 'ctrl_' + fieldname + '_' + language)
					{
						el.removeClass('hide');
					}
				});
			}
			// ELSE User clicked on the page
			
			$$('.tf_field_wrap').getElement('.tf_lng_list').removeClass('active');
		}
	});
	
	
	// User translates fields
	$$('.tf_field_wrap').getChildren('.tf_lng_field').each(function(el) {
		var fieldname = el.getParent('.tf_field_wrap').get('id').toString().replace('ctrl_', '');
		
		el.addEvent('keyup', function(el) {
			
			var obj = $(el.target);
			
			// Get active language from field
			var language = obj.get('id').replace('ctrl_' + fieldname + '_', '');
			
			// Get list item from active language
			var listItem = obj.getParent('.tf_field_wrap').getElement('.tf_lng_list').getElement('#lng_list_item_' + language);
			
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
