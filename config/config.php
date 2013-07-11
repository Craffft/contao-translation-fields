<?php 

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
 * Add CSS and JavaScript files
 */
if (TL_MODE == 'BE')
{
	$GLOBALS['TL_CSS'][]        = 'system/modules/translation_fields/assets/css/translationfields.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/translation_fields/assets/js/translationfields.js';
}


/**
 * Add new backend formfields
 */
$GLOBALS['BE_FFL']['TranslationInputUnit'] = 'TranslationInputUnit';
$GLOBALS['BE_FFL']['TranslationTextArea']  = 'TranslationTextArea';
$GLOBALS['BE_FFL']['TranslationTextField'] = 'TranslationTextField';
