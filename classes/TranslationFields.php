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
	 * strLanguage
	 * 
	 * @var string
	 * @access private
	 */
	private $strLanguage;
	
	
	/**
	 * strFallback
	 * 
	 * @var string
	 * @access private
	 */
	private $strFallback;
	
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		if (TL_MODE == 'FE')
		{
			global $objPage;
			
			$this->strLanguage = $objPage->language;
			$this->strFallback = $objPage->fallback;
		}
		
		if (TL_MODE == 'BE')
		{
			$this->import('BackendUser', 'User');
			
			$this->strLanguage = $this->User->language;
			$this->strFallback = 'en';
		}
	}
	
	
	/**
	 * setLanguage function.
	 * 
	 * @access public
	 * @param mixed $strLanguage
	 * @return void
	 */
	public function setLanguage($strLanguage)
	{
		if (strlen($strLanguage) < 1)
		{
			return;
		}
		
		$this->strLanguage = $strLanguage;
	}
	
	
	/**
	 * translateDCObject function.
	 * 
	 * @access public
	 * @param object $objDC
	 * @param string $strTable
	 * @return void
	 */
	public function translateDCObject($objDC, $strTable)
	{
		// Load DC
		$this->loadDataContainer($strTable);
		
		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				switch ($arrValues['inputType'])
				{
					case 'TranslationInputUnit':
						$objDC->$field = $this->translateField($objDC->$field);
					break;
					
					case 'TranslationTextArea':
						$objDC->$field = $this->translateField($objDC->$field);
					break;
					
					case 'TranslationTextField':
						$objDC->$field = $this->translateField($objDC->$field);
					break;
				}
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
	 * @return void
	 */
	public function translateDCArray($arrDC, $strTable)
	{
		// Load DC
		$this->loadDataContainer($strTable);

		if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0)
		{
			foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues)
			{
				switch ($arrValues['inputType'])
				{
					case 'TranslationTextField':
						$arrDC[$field] = $this->translateField($arrDC[$field]);
					break;
				}
			}
		}
		
		return $arrDC;
	}
	
	
	/**
	 * translateField function.
	 * 
	 * @access public
	 * @param mixed $varValue
	 * @return void
	 */
	public function translateField($varValue)
	{
		$varValue = deserialize($varValue);
		
		if (is_array($varValue))
		{
			if (strlen($varValue[$this->strLanguage]) > 0)
			{
				return $varValue[$this->strLanguage];
			}
			else if (strlen($varValue[$this->strFallback]) > 0)
			{
				return $varValue[$this->strFallback];
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
}
