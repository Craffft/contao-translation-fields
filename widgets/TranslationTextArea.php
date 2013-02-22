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
 * Class TranslationTextArea 
 *
 * @copyright  Daniel Kiesel 2013 
 * @author     Daniel Kiesel 
 * @package    translation_fields
 */
class TranslationTextArea extends \TextArea
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
	protected $blnForAttribute = true;

	/**
	 * Rows
	 * @var integer
	 */
	protected $intRows = 12;

	/**
	 * Columns
	 * @var integer
	 */
	protected $intCols = 80;

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

			case 'rows':
				$this->intRows = $varValue;
				break;

			case 'cols':
				$this->intCols = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
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
			$rte = false;

			// Register the field name for rich text editor usage
			if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['rte']))
			{
				list ($file, $type) = explode('|', $this->rte);
				$key = 'ctrl_' . $this->strId.'_'.$value;

				$GLOBALS['TL_RTE'][$file][$key] = array
				(
					'id'   => $key,
					'file' => $file,
					'type' => $type
				);

				$rte = true;
			}

			$arrFields[] = sprintf('<textarea name="%s[%s]" id="ctrl_%s" class="tf_lng_field tl_textarea tl_textarea_%s%s" rows="%s" cols="%s"%s onfocus="Backend.getScrollOffset()">%s</textarea>',
									$this->strName,
									$value,
									$this->strId.'_'.$value,
									$value,
									($i > 0) ? ' hide' : '',
									$this->intRows,
									$this->intCols,
									$this->getAttributes(),
									specialchars(@$this->varValue[$value]));
			$i++;
		}

		return sprintf('<div id="ctrl_%s" class="tf_field_wrap tf_textarea_wrap%s%s">%s%s%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						($rte ? ' rte' : ''),
						implode(' ', $arrFields),
						$strLngButton,
						$strLngList,
						$this->wizard);
	}
}
