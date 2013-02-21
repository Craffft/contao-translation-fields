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
//dump($GLOBALS['TL_DCA']['tl_module']['fields']['headline']);

$GLOBALS['TL_DCA']['tl_module']['palettes']['navigation'] = str_replace(',headline', ',headline,TranslationTextArea1,TranslationTextArea2,TranslationTextArea3', $GLOBALS['TL_DCA']['tl_module']['palettes']['navigation']);


$GLOBALS['TL_DCA']['tl_module']['fields']['name']['inputType'] = 'TranslationTextField';
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['sql'] = 'blob NULL';


$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['inputType'] = 'TranslationInputUnit';
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['sql'] = 'blob NULL';


$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1'] = $GLOBALS['TL_DCA']['tl_module']['fields']['name'];
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1']['inputType'] = 'TranslationTextArea';
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1']['sql'] = 'blob NULL';
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1']['eval']['rte'] = '';
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1']['eval']['tl_class'] = '';

$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea2'] = $GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1'];
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea2']['eval']['rte'] = 'tinyFlash';

$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea3'] = $GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea1'];
$GLOBALS['TL_DCA']['tl_module']['fields']['TranslationTextArea3']['eval']['rte'] = 'tinyMCE';



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