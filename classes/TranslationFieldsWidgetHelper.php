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
 * Class TranslationFieldsWidgetHelper
 *
 * @copyright  Daniel Kiesel 2013
 * @author     Daniel Kiesel
 * @package    translation_fields
 */
class TranslationFieldsWidgetHelper extends \Backend
{

	/**
	 * arrLng
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access private
	 * @static
	 */
	private static $arrLng = array();


	/**
	 * addFallbackValueToEmptyField function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $varInput
	 * @return mixed
	 */
	public static function addFallbackValueToEmptyField($varInput)
	{
		if (is_array($varInput))
		{
			// Add fallback text to other languages
			if (count($varInput) > 0)
			{
				$strFallbackValue = $varInput[key($varInput)];

				foreach($varInput as $key => $value)
				{
					if (strlen($value) < 1)
					{
						$varInput[$key] = $strFallbackValue;
					}
				}
			}
		}

		return $varInput;
	}


	/**
	 * getTranslationLanguages function.
	 * 
	 * @access public
	 * @static
	 * @param bool $blnReload (default: false)
	 * @return array
	 */
	public static function getTranslationLanguages($blnReload = false)
	{
		if ($blnReload || !is_array(self::$arrLng) || count(self::$arrLng) < 1)
		{
			self::setTranslationLanguages();
		}

		return self::$arrLng;
	}


	/**
	 * setTranslationLanguages function.
	 * 
	 * @access private
	 * @static
	 * @return void
	 */
	private static function setTranslationLanguages()
	{
		// Get all languages
		$arrLanguages = \System::getLanguages();

		// Get all used languages
		$arrLng = array();

		$objRootPages = \PageModel::findRootPages();

		if ($objRootPages !== null)
		{
			while ($objRootPages->next())
			{
				$arrLng[$objRootPages->language] = $arrLanguages[$objRootPages->language];
			}
		}

		// If langauge array is empty
		if (count($arrLng) < 1)
		{
			// Set all available languages
			$arrLng = \System::getLanguages(true);

			// Set the language of the user to the top
			if (\BackendUser::getInstance()->language != null)
			{
				// Get langauge value
				$strLngValue = $arrLng[\BackendUser::getInstance()->language];

				// Remove the current language from the array
				unset($arrLng[\BackendUser::getInstance()->language]);

				// Add old array to a temp array
				$arrLngTemp = $arrLng;

				// Generate a new array
				$arrLng = array(\BackendUser::getInstance()->language => $strLngValue);

				// Merge the old array into the new array
				$arrLng = array_merge($arrLng, $arrLngTemp);
			}
		}

		self::$arrLng = $arrLng;
	}


	/**
	 * getInputTranslationLanguages function.
	 * 
	 * @access public
	 * @static
	 * @param array $varValue
	 * @param bool $blnReload (default: false)
	 * @return array
	 */
	public static function getInputTranslationLanguages($varValue, $blnReload = false)
	{
		if ($blnReload || !is_array(self::$arrLng) || count(self::$arrLng) < 1)
		{
			self::setTranslationLanguages();
		}

		if (!is_array($varValue))
		{
			$varValue = array();
		}

		// Set new inputs array
		$arrLngInputs = self::$arrLng;

		// Merge value array languages into inputs array
		/*if (count($varValue) > 0)
		{
			$arrLngInputs = array_merge($arrLngInputs, $varValue);
		}*/

		// Get array keys
		$arrLngInputs = array_keys($arrLngInputs);

		return $arrLngInputs;
	}


	/**
	 * getCurrentTranslationLanguageButton function.
	 *
	 * @access public
	 * @static
	 * @return string
	 */
	public static function getCurrentTranslationLanguageButton()
	{
		// Get current translation languages
		$arrLngKeys = array_keys(self::$arrLng);

		// Generate current translation language button
		$strButton = sprintf('<span class="tf_button"><img src="system/modules/translation_fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s"></span>',
						$arrLngKeys[0],
						self::$arrLng[$arrLngKeys[0]]);

		return $strButton;
	}


	/**
	 * getTranslationLanguagesList function.
	 *
	 * @access public
	 * @static
	 * @param string $strId
	 * @param array $varValue
	 * @return string
	 */
	public static function getTranslationLanguagesList($varValue)
	{
		if (!is_array($varValue))
		{
			$varValue = array();
		}

		// Generate langauge list
		$arrLngList = array();
		$i = 0;

		foreach (self::$arrLng as $key => $value)
		{
			$strLngIcon = sprintf('<img src="system/modules/translation_fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s">',
							$key,
							$value);

			$arrLngList[] = sprintf('<li id="lng_list_item_%s" class="tf_lng_item%s">%s%s</li>',
							$key,
							(strlen(specialchars(@$varValue[$key])) > 0) ? ' translated' : '',
							$strLngIcon,
							$value);
			$i++;
		}

		$strLngList = sprintf('<ul class="tf_lng_list">%s</ul>',
						implode(' ', $arrLngList));

		return $strLngList;
	}
}
