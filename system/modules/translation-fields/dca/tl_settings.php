<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2014 Leo Feyer
 * 
 * @package    translation-fields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL 
 * @copyright  Daniel Kiesel 2013-2016
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'chooseTranslationLanguages';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('inactiveModules;', 'inactiveModules;{translation-fields_legend},dontfillEmptyTranslationFields,chooseTranslationLanguages;', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['chooseTranslationLanguages'] = 'translationLanguages';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['dontfillEmptyTranslationFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['dontfillEmptyTranslationFields'],
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['chooseTranslationLanguages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['chooseTranslationLanguages'],
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['translationLanguages'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['translationLanguages'],
	'inputType'               => 'checkboxWizard',
	'options'                 => \System::getLanguages(),
	'eval'                    => array('mandatory'=>true, 'multiple'=>true)
);
