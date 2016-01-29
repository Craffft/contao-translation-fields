<?php

/*
 * This file is part of the TranslationFields Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TranslationFields;

class TranslationInputUnit extends \InputUnit
{
    protected $blnSubmitInput = true;
    protected $strTemplate = 'be_widget';
    protected $arrUnits = array();

    /**
     * @param string $strKey
     * @param mixed $varValue
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

            case 'options':
                $this->arrUnits = deserialize($varValue);
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * @param mixed $varInput
     * @return mixed
     */
    protected function validator($varInput)
    {
        // Get array with language id
        $arrData = ($this->activeRecord) ? $this->activeRecord->{$this->strName} : $GLOBALS['TL_CONFIG'][$this->strName];

        if (is_array($varInput['value'])) {
            // Check if translation fields should not be empty saved
            if (!$GLOBALS['TL_CONFIG']['dontfillEmptyTranslationFields']) {
                // Fill all empty fields with the content of the fallback field
                $varInput['value'] = \TranslationFieldsWidgetHelper::addFallbackValueToEmptyField($varInput['value']);
                parent::validator($varInput['value']);
            } else {
                // Check only the first field
                parent::validator($varInput['value'][key($varInput['value'])]);
            }
        }

        $arrData = deserialize($arrData);

        // Save values and return fid
        $varInput['value'] = \TranslationFieldsWidgetHelper::saveValuesAndReturnFid($varInput['value'],
            $arrData['value']);

        return $varInput;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $arrUnits = array();

        foreach ($this->arrUnits as $arrUnit) {
            $arrUnits[] = sprintf('<option value="%s"%s>%s</option>',
                specialchars($arrUnit['value']),
                $this->isSelected($arrUnit),
                $arrUnit['label']);
        }

        if (!is_array($this->varValue)) {
            $this->varValue = array('value' => $this->varValue);
        }

        // Get languages array with values
        $this->varValue['value'] = \TranslationFieldsWidgetHelper::getTranslationsByFid($this->varValue['value']);

        // Generate langauge fields
        $arrLngInputs = \TranslationFieldsWidgetHelper::getInputTranslationLanguages($this->varValue['value']);

        $arrFields = array();
        $i = 0;

        foreach ($arrLngInputs as $value) {
            $arrFields[] = sprintf('<div class="tf_field_wrap tf_field_wrap_%s%s"><input type="text" name="%s[value][%s]" id="ctrl_%s" class="tf_field tl_text_unit" value="%s"%s onfocus="Backend.getScrollOffset()"></div>',
                $value,
                ($i > 0) ? ' hide' : '',
                $this->strName,
                $value,
                $this->strId . '_' . $value,
                specialchars(@$this->varValue['value'][$value]),
                $this->getAttributes());
            $i++;
        }

        $strUnit = sprintf('<select name="%s[unit]" class="tl_select_unit" onfocus="Backend.getScrollOffset()">%s</select>',
            $this->strName,
            implode('', $arrUnits));

        // Get language button
        $strLngButton = \TranslationFieldsWidgetHelper::getCurrentTranslationLanguageButton();

        // Get language list
        $strLngList = \TranslationFieldsWidgetHelper::getTranslationLanguagesList($this->varValue);

        return sprintf('<div id="ctrl_%s" class="tf_wrap tf_text_unit_wrap%s">%s %s%s%s</div>%s',
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            implode(' ', $arrFields),
            $strUnit,
            $strLngButton,
            $strLngList,
            $this->wizard);
    }
}
