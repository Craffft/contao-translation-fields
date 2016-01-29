<?php

/*
 * This file is part of the TranslationFields Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'chooseTranslationLanguages';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'inactiveModules;',
    'inactiveModules;{translation-fields_legend},dontfillEmptyTranslationFields,chooseTranslationLanguages;',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['chooseTranslationLanguages'] = 'translationLanguages';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['dontfillEmptyTranslationFields'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['dontfillEmptyTranslationFields'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'm12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['chooseTranslationLanguages'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['chooseTranslationLanguages'],
    'inputType' => 'checkbox',
    'eval'      => array('submitOnChange' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['translationLanguages'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['translationLanguages'],
    'inputType' => 'checkboxWizard',
    'options'   => \System::getLanguages(),
    'eval'      => array('mandatory' => true, 'multiple' => true)
);
