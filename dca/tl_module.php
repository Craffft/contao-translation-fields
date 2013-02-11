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
 * Table tl_translation_fields 
 */
//dump($GLOBALS['TL_DCA']['tl_module']['fields']['name']);
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['inputType'] = 'TranslationTextField';
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['sql'] = 'blob NULL';
//$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['inputType'] = 'TranslationTextField'; // inputUnit
//$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['sql'] = 'blob NULL';
$GLOBALS['TL_DCA']['tl_module']['list']['sorting']['child_record_callback'] =  array('tl_module_translation_fields', 'listModule');


/**
 * tl_module_translation_fields class.
 * 
 * @extends tl_module
 */
class tl_module_translation_fields extends tl_module
{
	/**
	 * listModule function.
	 * 
	 * @access public
	 * @param array $row
	 * @return string
	 */
	public function listModule($row)
	{
		$objTranslationFields = new \TranslationFields();
		$row = $objTranslationFields->translateDCArray($row, 'tl_module');
		
		return parent::listModule($row);
	}
}