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
	 * validator function.
	 * 
	 * @access protected
	 * @param mixed $varInput
	 * @return int
	 */
	protected function validator($varInput)
	{
		// Fill all empty fields with the content of the fallback field
		$varInput = \TranslationFieldsWidgetHelper::addFallbackValueToEmptyField($varInput);

		parent::validator($varInput);

		if (is_array($varInput))
		{
			if (!parent::hasErrors())
			{
				// Save values and return fid
				return \TranslationFieldsWidgetHelper::saveValuesAndReturnFid($varInput, $this->activeRecord->{$this->strName});
			}
		}

		return $this->activeRecord->{$this->strName};
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// Get languages array with values
		$this->varValue = \TranslationFieldsWidgetHelper::getTranslationsByFid($this->varValue);

		// Generate langauge fields
		$arrLngInputs = \TranslationFieldsWidgetHelper::getInputTranslationLanguages($this->varValue);

		$arrFields = array();
		$i = 0;

		foreach ($arrLngInputs as $value)
		{
			$strScript = '';
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

				$strScript = sprintf("\n<script>tinyMCE.execCommand('mceAddControl', false, '%s');$('%s').erase('required')</script>",
										$key,
										$key);

				$rte = true;
			}

			$arrFields[] = sprintf('<div class="tf_field_wrap tf_field_wrap_%s%s"><textarea name="%s[%s]" id="ctrl_%s" class="tf_field tl_textarea" rows="%s" cols="%s"%s onfocus="Backend.getScrollOffset()">%s</textarea>%s</div>',
									$value,
									($i > 0) ? ' hide' : '',
									$this->strName,
									$value,
									$this->strId.'_'.$value,
									$this->intRows,
									$this->intCols,
									$this->getAttributes(),
									specialchars((\Input::post($this->strName)[$value] !== null) ? @\Input::post($this->strName)[$value] : @$this->varValue[$value]), // see #4979
									$strScript);
			$i++;
		}

		// Get language button
		$strLngButton = \TranslationFieldsWidgetHelper::getCurrentTranslationLanguageButton();

		// Get language list
		$strLngList = \TranslationFieldsWidgetHelper::getTranslationLanguagesList($this->varValue);

		return sprintf('<div id="ctrl_%s" class="tf_wrap tf_textarea_wrap%s%s">%s%s%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						($rte ? ' rte' : ''),
						implode(' ', $arrFields),
						$strLngButton,
						$strLngList,
						$this->wizard);
	}
}
