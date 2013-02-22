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
 * Class TranslationTextField 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
 */
class TranslationTextField extends \TextField
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Add a for attribute
	 * @var boolean
	 */
	protected $blnForAttribute = false;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'maxlength':
				if ($varValue > 0)
				{
					$this->arrAttributes['maxlength'] = $varValue;
				}
				break;

			case 'mandatory':
				if ($varValue)
				{
					$this->arrAttributes['required'] = 'required';
				}
				else
				{
					unset($this->arrAttributes['required']);
				}
				parent::__set($strKey, $varValue);
				break;

			case 'placeholder':
				$this->arrAttributes['placeholder'] = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		if (is_array($varInput))
		{
			$varInput = \TranslationWidgetHelper::addFallbackValueToEmptyField($varInput);
			
			return parent::validator($varInput);
		}

		return parent::validator(trim($varInput));
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// Get translation languages
		$objTranslationWidgetHelper = new \TranslationWidgetHelper();
		$arrLng = $objTranslationWidgetHelper->getTranslationLanguages();

		$type = $this->hideInput ? 'password' : 'text';

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		// Get language button
		$strLngButton = $objTranslationWidgetHelper->getCurrentTranslationLanguageButton();

		// Get language list
		$strLngList = $objTranslationWidgetHelper->getTranslationLanguagesList($this->varValue);

		// Generate langauge fields
		$arrLngInputs = $objTranslationWidgetHelper->getInputTranslationLanguages($this->varValue);

		$arrFields = array();
		$i = 0;

		foreach ($arrLngInputs as $value)
		{
			$arrFields[] = sprintf('<input type="%s" name="%s[%s]" id="ctrl_%s" class="tf_lng_field tl_text tl_text_%s%s" value="%s"%s onfocus="Backend.getScrollOffset()">',
									$type,
									$this->strName,
									$value,
									$this->strId.'_'.$value,
									$value,
									($i > 0) ? ' hide' : '',
									specialchars(@$this->varValue[$value]), // see #4979
									$this->getAttributes());
			$i++;
		}


		return sprintf('<div id="ctrl_%s" class="tf_field_wrap tf_text_wrap%s">%s%s%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						implode(' ', $arrFields),
						$strLngButton,
						$strLngList,
						$this->wizard);
	}
}
