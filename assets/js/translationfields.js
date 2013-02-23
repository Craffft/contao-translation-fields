
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
		var field     = obj.getParent('.tf_wrap');
		
		if (field != undefined)
		{
			var fieldname = field.get('id').replace('ctrl_', '');
			
			if (obj == null)
			{
				return;
			}
			
			// User clicked on the flag button
			if (obj.hasClass('tf_button') || obj.getParent('.tf_button') != null)
			{
				if (field.getElement('.tf_lng_list').hasClass('active'))
				{
					// Close language list
					field.getElement('.tf_lng_list').removeClass('active');
				}
				else
				{
					$$('.tf_wrap .tf_lng_list.active').each(function(el) {
						el.removeClass('active');
					});
					
					// Open language list
					field.getElement('.tf_lng_list').addClass('active');
				}
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
					field.getChildren('.tf_field_wrap').each(function(el) {
						el.addClass('hide');
						
						if (el.hasClass('tf_field_wrap_' + language))
						{
							el.removeClass('hide');
						}
					});
				}
				// ELSE User clicked on the page
				
				$$('.tf_wrap').getElement('.tf_lng_list').removeClass('active');
			}
		}
	});
	
	
	// User translates fields
	$$('.tf_wrap').getChildren('.tf_field_wrap').each(function(el) {
		var fieldname = el.getParent('.tf_wrap').get('id').toString().replace('ctrl_', '');
		
		el.addEvent('keyup', function(el) {
			var obj = $(el.target);
			
			// Get active language from field
			var language = obj.get('id').replace('ctrl_' + fieldname + '_', '');
			
			// Get list item from active language
			var listItem = obj.getParent('.tf_wrap').getElement('.tf_lng_list').getElement('#lng_list_item_' + language);
			
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
