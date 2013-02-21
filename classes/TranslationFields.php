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
	 * @access public
	 * @param string $strInputType
	 * @param array $varValue
	 * @return string
	 */
	public function translateField($strInputType, $varValue)
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
	 * @param object $objDC
	 * @param string $strTable
	 * @return object
	 */
	public function translateDCObject($objDC, $strTable)
	{
		// Load DC
		$this->loadDataContainer($strTable);
		
		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				$objDC->$field = $this->translateField($arrValues['inputType'], $objDC->$field);
			}
		}
		
		return $objDC;
	}
	
	
	/**
	 * translateDCArray function.
	 * 
	 * @access public
	 * @param array $arrDC
	 * @param string $strTable
	 * @return array
	 */
	public function translateDCArray($arrDC, $strTable)
	{
		// Load DC
		$this->loadDataContainer($strTable);

		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				$arrDC[$field] = $this->translateField($arrValues['inputType'], $arrDC[$field]);
			}
		}
		
		return $arrDC;
	}
}
