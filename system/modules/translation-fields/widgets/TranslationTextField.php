<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    translation-fields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2014
 */

/**
 * Namespace
 */
namespace TranslationFields;

/**
 * Class TranslationTextField
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
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
        switch ($strKey) {
            case 'maxlength':
                if ($varValue > 0) {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;

            case 'mandatory':
                if ($varValue) {
                    $this->arrAttributes['required'] = 'required';
                } else {
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
     * validator function.
     *
     * @access protected
     * @param mixed $varInput
     * @return int
     */
    protected function validator($varInput)
    {
        // Get language id
        $intId = ($this->activeRecord) ? $this->activeRecord->{$this->strName} : $GLOBALS['TL_CONFIG'][$this->strName];

        // Check if translation fields should not be empty saved
        if (!$GLOBALS['TL_CONFIG']['dontfillEmptyTranslationFields']) {
            // Fill all empty fields with the content of the fallback field
            $varInput = \TranslationFieldsWidgetHelper::addFallbackValueToEmptyField($varInput);
            parent::validator($varInput);
        } else {
            // Check only the first field
            parent::validator($varInput[key($varInput)]);
        }

        // Check if array
        if (is_array($varInput)) {
            if (!parent::hasErrors()) {
                // Save values and return fid
                return \TranslationFieldsWidgetHelper::saveValuesAndReturnFid($varInput, $intId);
            }
        }

        return $intId;
    }

    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        $type = $this->hideInput ? 'password' : 'text';

        // Get post array
        $arrPost = \Input::post($this->strName);

        // Get languages array with values
        $this->varValue = \TranslationFieldsWidgetHelper::getTranslationsByFid($this->varValue);

        // Generate langauge fields
        $arrLngInputs = \TranslationFieldsWidgetHelper::getInputTranslationLanguages($this->varValue);

        $arrFields = array();
        $i = 0;

        foreach ($arrLngInputs as $value) {
            $arrFields[] = sprintf('<div class="tf_field_wrap tf_field_wrap_%s%s"><input type="%s" name="%s[%s]" id="ctrl_%s" class="tf_field tl_text" value="%s"%s onfocus="Backend.getScrollOffset()"></div>',
                $value,
                ($i > 0) ? ' hide' : '',
                $type,
                $this->strName,
                $value,
                $this->strId . '_' . $value,
                specialchars(($arrPost[$value] !== null) ? $arrPost[$value] : @$this->varValue[$value]), // see #4979
                $this->getAttributes());
            $i++;
        }

        // Get language button
        $strLngButton = \TranslationFieldsWidgetHelper::getCurrentTranslationLanguageButton();

        // Get language list
        $strLngList = \TranslationFieldsWidgetHelper::getTranslationLanguagesList($this->varValue);

        return sprintf('<div id="ctrl_%s" class="tf_wrap tf_text_wrap%s">%s%s%s</div>%s',
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            implode(' ', $arrFields),
            $strLngButton,
            $strLngList,
            $this->wizard);
    }
}
