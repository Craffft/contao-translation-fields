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
 * Class TranslationFields 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
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
	public static function translateValue($varValue)
	{
		$varValue = deserialize($varValue);
		
		if (is_array($varValue))
		{
			if (strlen($varValue[$GLOBALS['TL_LANGUAGE']]) > 0)
			{
				return $varValue[$GLOBALS['TL_LANGUAGE']];
			}
			else if (strlen($varValue['en']) > 0)
			{
				return $varValue['en'];
			}
			else
			{
				if (count($varValue) > 0)
				{
					foreach ($varValue as $key => $value)
					{
						if (strlen($value) > 0)
						{
							return $varValue[$key];
						}
					}
				}
				
				return '__NO_TRANSLATION_FOUND__';
			}
		}
		
		return $varValue;
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
	 * @param string $strTable
	 * @return object
	 */
	public static function translateDCObject($objDC, $strTable)
	{
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


	public static function prepareDCA($strName = null)
	{
		$arrTranslationFieldsConfig = deserialize($GLOBALS['TL_CONFIG']['translation_fields']);
		
		if (is_array($arrTranslationFieldsConfig) && count($arrTranslationFieldsConfig) > 0)
		{
			$arrTables = array();
			
			foreach ($arrTranslationFieldsConfig as $k => $v)
			{
				$arrExplode = explode('::', $v);
				$strTable = $arrExplode[0];
				$strField = $arrExplode[1];
				
				if (empty($strTable))
				{
					continue;
				}

				if (empty($strField))
				{
					continue;
				}

				if (!($strName === null || $strName == $strTable))
				{
					continue;
				}

				$strInputType = $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'];

				if (!strlen($strInputType))
				{
					break;
				}

				switch ($strInputType)
				{
					case 'text':
						$strInputType = 'TranslationTextField';
					break;

					case 'textarea':
						$strInputType = 'TranslationTextArea';
					break;

					case 'inputUnit':
						$strInputType = 'TranslationInputUnit';
					break;
				}

				// Add strTable to arrTables
				$arrTables[] = $strTable;

				$GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'] = $strInputType;
			}

			$arrTables = array_unique($arrTables);

			if (is_array($arrTables) && count($arrTables) > 0)
			{
				foreach ($arrTables as $k => $v)
				{
					$arrChildRecordCallback = $GLOBALS['TL_DCA'][$v]['list']['sorting']['child_record_callback'];

					if (is_array($arrChildRecordCallback) && count($arrChildRecordCallback) == 2)
					{
						// TranslationFields
						$GLOBALS['TL_DCA'][$v]['list']['sorting']['child_record_callback_original'] = $GLOBALS['TL_DCA'][$v]['list']['sorting']['child_record_callback'];
						$GLOBALS['TL_DCA'][$v]['list']['sorting']['child_record_callback'] = array('TranslationFields', 'listViewCallback');
					}
				}
			}
		}
	}


	/**
	 * listViewCallback function.
	 * 
	 * @access public
	 * @param array $row
	 * @return string
	 */
	public function listViewCallback($row)
	{
		$strTable = \Input::get('table');
		$arrCallback = $GLOBALS['TL_DCA'][$strTable]['list']['sorting']['child_record_callback_original'];
		$strReturn = '-';

		if (is_array($arrCallback) && count($arrCallback) == 2)
		{
			// Translate fields
			$row = \TranslationFields::translateDCArray($row, $strTable);

			$this->import($arrCallback[0]);
			$strReturn = $this->$arrCallback[0]->$arrCallback[1]($row);
		}

		return $strReturn;
	}
}
