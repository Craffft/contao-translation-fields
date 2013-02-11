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
 * Namespace
 */
namespace TranslationFields;

/**
 * Class TranslationFields 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
 */
abstract class Module extends \Contao\Module
{
	public function __construct($objModule, $strColumn='main')
	{
		$objTranslationFields = new \TranslationFields();
		$objModule = $objTranslationFields->translateDCObject($objModule, 'tl_module');
		
		parent::__construct($objModule, $strColumn);
	}
}
