<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2014 Leo Feyer
 * 
 * @package    translation_fields 
 * @author     Daniel Kiesel <https://github.com/icodr8> 
 * @license    LGPL 
 * @copyright  Daniel Kiesel 2013-2014 
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'chooseTranslationLanguages';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('inactiveModules;', 'inactiveModules;{translation_fields_legend},chooseTranslationLanguages;', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['chooseTranslationLanguages'] = 'translationLanguages';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['chooseTranslationLanguages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['chooseTranslationLanguages'],
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['translationLanguages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['translationLanguages'],
	'inputType'               => 'checkbox',
	'options'                 => \System::getLanguages(),
	'eval'                    => array('multiple'=>true)
);
