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


$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('inactiveModules;', 'inactiveModules;{translation_fields_legend},translation_fields;', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_settings']['fields']['translation_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['translation_fields'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_settings_translation_fields', 'getPotentialTranslationFields'),
	'eval'                    => array('multiple'=>true),
	'save_callback' => array
	(
		array('tl_settings_translation_fields', 'updateTranslationFields')
	)
);


/**
 * tl_settings_translation_fields class.
 * 
 * @extends Backend
 */
class tl_settings_translation_fields extends Backend
{
	/**
	 * getPotentialTranslationFields function.
	 * 
	 * @access public
	 * @return array
	 */
	public function getPotentialTranslationFields()
	{
		$included = array();

		foreach ($this->Config->getActiveModules() as $strModule)
		{
			$strDir = sprintf('%s/system/modules/%s/dca/', TL_ROOT, $strModule);

			if (!is_dir($strDir))
			{
				continue;
			}

			foreach (scan($strDir) as $strFile)
			{
				if ($strFile == '.htaccess' || in_array($strFile, $included))
				{
					continue;
				}

				$included[] = $strFile;
				$strTable = str_replace('.php', '', $strFile);

				$this->loadLanguageFile($strTable);
				$this->loadDataContainer($strTable);
			}
		}

		$arrReturn = array();

		// Get all translation fields
		foreach ($GLOBALS['TL_DCA'] as $k=>$v)
		{
			if (is_array($v['fields']))
			{
				$arrReturn[$k] = array();

				foreach ($v['fields'] as $kk=>$vv)
				{
					if (in_array($kk, $GLOBALS['TL_CONFIG']['ignoreFields']))
					{
						continue;
					}

					switch ($vv['inputType'])
					{
						case 'text':
						case 'textarea':
						case 'inputUnit':
						case 'TranslationTextField':
						case 'TranslationTextArea':
						case 'TranslationInputUnit':
							$arrReturn[$k][specialchars($k.'::'.$kk)] = $vv['label'][0] ?: $kk;
						break;
					}
				}
			}
		}

		ksort($arrReturn);
		return $arrReturn;
	}


	/**
	 * updateTranslationFields function.
	 * 
	 * @access public
	 * @param mixed $varValue
	 * @return mixed
	 */
	public function updateTranslationFields($varValue)
	{
		$arrValues = deserialize($varValue);

		if (is_array($arrValues) && count($arrValues) > 0)
		{
			foreach ($arrValues as $k => $v)
			{
				$arrExplode = explode('::', $v);
				$strTable = $arrExplode[0];
				$strField = $arrExplode[1];

				// Update SQL type
				// UNTERSCHEIDEN ZWISCHEN NEUEM UND ALTEM FELD
				// HIER DATEN FÜR FELDÄNDERUNGEN ANPASSEN
				dump(deserialize($GLOBALS['TL_CONFIG']['translation_fields']));
			}
		}

		return $varValue;
	}
}
