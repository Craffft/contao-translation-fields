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
	 * Disable the for attribute if the "multiple" option is set
	 * @param array
	 */
	public function __construct($arrAttributes=null)
	{
		// Import backend user
		$this->import('BackendUser', 'User');
		
		parent::__construct($arrAttributes);
	}


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
		// Get all languages
		$arrLanguages = $this->getLanguages();

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
			if ($this->User->language != null)
			{
				// Get langauge value
				$strLngValue = $arrLng[$this->User->language];
				
				// Remove the current language from the array
				unset($arrLng[$this->User->language]);
				
				// Add old array to a temp array
				$arrLngTemp = $arrLng;
				
				// Generate a new array
				$arrLng = array($this->User->language => $strLngValue);
				
				// Merge the old array into the new array
				$arrLng = array_merge($arrLng, $arrLngTemp);
			}
		}

		$type = $this->hideInput ? 'password' : 'text';

		if (!is_array($this->varValue))
		{
			$this->varValue = array($this->varValue);
		}

		// Set new inputs array
		$arrLngInputs = $arrLng;
		
		/* DIESE OPTION EVENTUELL ÜBER DIE EINSTELLUNGEN FESTLEGEN
		// Merge value array languages into inputs array
		if (count($this->varValue) > 0)
		{
			$arrLngInputs = array_merge($arrLngInputs, $this->varValue);
		}
		*/
		
		// Get array keys
		$arrLngInputs = array_keys($arrLngInputs);

		// Generate langauge fields
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


		$arrLngKeys = array_keys($arrLng);
		
		$strLngButton = sprintf('<span class="tf_button"><img src="system/modules/translation_fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s"></span>',
								$arrLngKeys[0],
								$arrLng[$arrLngKeys[0]]);


		// Generate langauge list
		$arrLngList = array();
		$i = 0;

		foreach ($arrLng as $key => $value)
		{
			$strLngIcon = sprintf('<img src="system/modules/translation_fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s">',
									$key,
									$value);
			
			$arrLngList[] = sprintf('<li id="lng_%s" class="tf_lng_item%s">%s%s</li>',
										$this->strId.'_'.$key,
										(strlen(specialchars(@$this->varValue[$key])) > 0) ? ' translated' : '',
										$strLngIcon,
										$value);
			$i++;
		}


		$strLngList = sprintf('<ul class="tf_lng_list">%s</ul>',
								implode(' ', $arrLngList));


		return sprintf('<div id="ctrl_%s" class="tf_field_wrap%s">%s%s%s</div>%s',
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						implode(' ', $arrFields),
						$strLngButton,
						$strLngList,
						$this->wizard);
	}
}
