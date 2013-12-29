<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    translation_fields
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFieldsPageModel
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @package    translation_fields
 */
class TranslationFieldsPageModel extends \Model
{

	/**
	 * strTable
	 * 
	 * @var string
	 * @access protected
	 * @static
	 */
	protected static $strTable = 'tl_page';


	/**
	 * findRootPages function.
	 * 
	 * @access public
	 * @static
	 * @param array
	 * @return object
	 */
	public static function findRootPages(array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.type='root'");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.fallback DESC, $t.sorting ASC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}
}
