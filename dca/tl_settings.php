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


class tl_settings_translation_fields extends Backend
{
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
							$arrReturn[$k][specialchars($kk)] = $vv['label'][0] ?: $kk;
						break;
					}
				}
			}
		}

		ksort($arrReturn);
		return $arrReturn;
	}


	public function updateTranslationFields($varValue)
	{
		return $varValue;
	}
}
