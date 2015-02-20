<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    translation-fields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2014
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFieldsModel
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class TranslationFieldsModel extends \Model
{

	/**
	 * Name of the table
	 * @var string
	 */
	protected static $strTable = 'tl_translation_fields';


	/**
	 * findOneByFidAndLanguage function.
	 *
	 * @access public
	 * @static
	 * @param int $intFid
	 * @param string $strLanguage
	 * @param array $arrOptions (default: array())
	 * @return object
	 */
	public static function findOneByFidAndLanguage($intFid, $strLanguage, array $arrOptions=array())
	{
		$t = static::$strTable;

		$arrColumns = array("$t.fid=? AND $t.language=?");
		$arrValues = array($intFid, $strLanguage);

		return static::findOneBy($arrColumns, $arrValues, $arrOptions);
	}


	/**
	 * getNextFid function.
	 *
	 * @access public
	 * @static
	 * @return int
	 */
	public static function getNextFid()
	{
		$t = static::$strTable;

		$intFid = \Database::getInstance()->prepare("SELECT (fid + 1) AS nextFid FROM $t ORDER BY fid DESC")->limit(1)->executeUncached()->nextFid;
		$intFid = ($intFid === null) ? 1 : $intFid;

		return $intFid;
	}
}
