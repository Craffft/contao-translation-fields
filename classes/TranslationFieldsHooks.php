<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   TranslationFields 
 * @author    Daniel Kiesel 
 * @license   LGPL 
 * @copyright Daniel Kiesel 2013 
 */


/**
 * Namespace
 */
namespace TranslationFields;

/**
 * Class TranslationFieldsHooks 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
 */
class TranslationFieldsHooks extends \Controller
{	
	public function TranslationFieldsLoadDataContainer($strName)
	{
		// Change input types to translation fields
		\TranslationFields::prepareDCA($strName);
	}
}
