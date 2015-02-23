<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    TranslationFields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2014
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFields
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class TranslationFields extends \Controller
{

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * translateValue function.
	 *
	 * @access public
	 * @static
	 * @param mixed $varValue
	 * @return string
	 */
	public static function translateValue($varValue, $strForceLanguage = '')
	{
		// Return value if it is already translated
		if (!is_numeric($varValue))
		{
			return $varValue;
		}

		$arrLanguages = array();

		// If force language is set than add it as first language param
		if (strlen($strForceLanguage))
		{
			$arrLanguages[] = $strForceLanguage;
		}

		// Add current langauge and default language to languages array
		$arrLanguages[] = $GLOBALS['TL_LANGUAGE'];
		$arrLanguages[] = 'en';

		// Get translation by current language and if it doesn't exist use the english translation
		foreach ($arrLanguages as $strLanguage)
		{
			$objTranslation = \TranslationFieldsModel::findOneByFidAndLanguage($varValue, $strLanguage);

			if ($objTranslation !== null)
			{
				return $objTranslation->content;
			}
		}

		// Get any translation
		$objTranslation = \TranslationFieldsModel::findOneByFid($varValue);

		if ($objTranslation !== null)
		{
			return $objTranslation->content;
		}

		return '';
	}


	/**
	 * translateField function.
	 *
	 * @access protected
	 * @static
	 * @param string $strInputType
	 * @param array $varValue
	 * @return string
	 */
	protected static function translateField($strInputType, $varValue)
	{
		switch ($strInputType)
		{
			case 'TranslationInputUnit':
				$varValue = deserialize($varValue);

				if (is_array($varValue))
				{
					if (is_array($varValue['value']) && count($varValue['value']) > 0)
					{
						$varValue['value'] = self::translateValue($varValue['value']);
					}
				}
			break;

			case 'TranslationTextArea':
			case 'TranslationTextField':
				$varValue = self::translateValue($varValue);
			break;
		}

		return $varValue;
	}


	/**
	 * translateDCObject function.
	 *
	 * @access public
	 * @static
	 * @param object $objDC
	 * @return object
	 */
	public static function translateDCObject($objDC)
	{
		// Get table
		$strTable = $objDC->current()->getTable();

		// Load current data container
		$objTranslationController = new \TranslationController();
		$objTranslationController->loadDataContainer($strTable);

		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				$objDC->$field = self::translateField($arrValues['inputType'], $objDC->$field);
			}
		}

		return $objDC;
	}


	/**
	 * translateDCArray function.
	 *
	 * @access public
	 * @static
	 * @param array $arrDC
	 * @param string $strTable
	 * @return array
	 */
	public static function translateDCArray($arrDC, $strTable)
	{
		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				$arrDC[$field] = self::translateField($arrValues['inputType'], $arrDC[$field]);
			}
		}

		return $arrDC;
	}


	/**
	 * getTranslationsByFid function.
	 *
	 * @access public
	 * @static
	 * @param int $intFid
	 * @return array
	 */
	public static function getTranslationsByFid($intFid)
	{
		$arrData = array();

		// Get translation object
		$objTranslation = \TranslationFieldsModel::findByFid($intFid);

		if ($objTranslation !== null)
		{
			while ($objTranslation->next())
			{
				$arrData[$objTranslation->language] = $objTranslation->content;
			}
		}

		return $arrData;
	}
}
