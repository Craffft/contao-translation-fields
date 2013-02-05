<?php 

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
 * Table tl_language_fields 
 */
//dump($GLOBALS['TL_DCA']['tl_module']['fields']['name']);
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['inputType'] = 'languageText';

$GLOBALS['TL_DCA']['tl_module']['fields']['name']['wizard'] = array
(
	array('LanguageFields', 'languagePicker')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['name']['eval']['tl_class'] = 'w50 wizard';
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['eval']['datepicker'] = true;
