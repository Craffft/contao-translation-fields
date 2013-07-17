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
 * Class TranslationFieldsModel 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
 */
class TranslationFieldsModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_translation_fields';


	public static function findOneByFidAndLanguage($intFid, $strLanguage, array $arrOptions=array())
	{
		$t = static::$strTable;

		$arrColumns = array("$t.fid=? AND $t.language=?");
		$arrValues = array($intFid, $strLanguage);

		return static::findOneBy($arrColumns, $arrValues, $arrOptions);
	}
}
