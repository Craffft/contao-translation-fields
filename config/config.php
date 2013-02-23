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


if (TL_MODE == 'BE')
{
	$GLOBALS['TL_CSS'][]        = 'system/modules/translation_fields/assets/css/translationfields.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/translation_fields/assets/js/translationfields.js';
}

$GLOBALS['BE_FFL']['TranslationInputUnit'] = 'TranslationInputUnit';
$GLOBALS['BE_FFL']['TranslationTextArea']  = 'TranslationTextArea';
$GLOBALS['BE_FFL']['TranslationTextField'] = 'TranslationTextField';


/**
 * List of the ignored fields which are not be shown in tl_settings
 */
$GLOBALS['TL_CONFIG']['ignoreFields'] = array(
	'alias',
	'start',
	'stop',
	'url',
	'email',
	'username',
	'name',
	'imageUrl',
	'cssClass'
);
