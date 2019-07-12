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

class TranslationTextArea extends \TextArea
{
    protected $blnSubmitInput = true;
    protected $blnForAttribute = true;
    protected $intRows = 12;
    protected $intCols = 80;
    protected $strTemplate = 'be_widget';

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
     * @param $varInput
     * @return mixed
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
     * @return string
     * @throws \Exception
     */
    public function generate()
    {
        // Get post array
        $arrPost = \Input::post($this->strName);

        // Get languages array with values
        $this->varValue = \TranslationFieldsWidgetHelper::getTranslationsByFid($this->varValue);

        // Generate langauge fields
        $arrLngInputs = \TranslationFieldsWidgetHelper::getInputTranslationLanguages($this->varValue);

        $arrFields = array();

        for ($i = 0; $i < count($arrLngInputs); $i++) {
            $value = $arrLngInputs[$i];

            $strRte = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['rte'];
            $key = 'ctrl_' . $this->strId . '_' . $value;

            $strScript = $this->getRteScriptByTranslatedField($strRte, $key);

            $arrFields[] = sprintf('<div class="tf_field_wrap tf_field_wrap_%s%s"><textarea name="%s[%s]" id="%s" class="tf_field tl_textarea" rows="%s" cols="%s"%s onfocus="Backend.getScrollOffset()">%s</textarea>%s</div>',
                $value,
                ($i > 0) ? ' hide' : '',
                $this->strName,
                $value,
                $key,
                $this->intRows,
                $this->intCols,
                $this->getAttributes(),
                \StringUtil::specialchars(($arrPost[$value] !== null) ? $arrPost[$value] : @$this->varValue[$value]), // see #4979
                $strScript);
        }

        // Get language button
        $strLngButton = \TranslationFieldsWidgetHelper::getCurrentTranslationLanguageButton();

        // Get language list
        $strLngList = \TranslationFieldsWidgetHelper::getTranslationLanguagesList($this->varValue);

        return sprintf('<div id="ctrl_%s_tf" class="tf_wrap tf_textarea_wrap%s%s">%s%s%s</div>%s',
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            (!empty($this->rte) ? ' rte' : ''),
            implode(' ', $arrFields),
            $strLngButton,
            $strLngList,
            $this->wizard);
    }

    /**
     * @param $rte
     * @param $selector
     * @return string
     * @throws \Exception
     */
    protected function getRteScriptByTranslatedField($rte, $selector)
    {
        $updateMode = '';

        // Replace the textarea with an RTE instance
        if (!empty($rte)) {
            list ($file, $type) = explode('|', $rte, 2);

            if (!file_exists(TL_ROOT . '/system/config/' . $file . '.php')) {
                throw new \Exception(sprintf('Cannot find editor configuration file "%s.php"', $file));
            }

            // Backwards compatibility
            $language = substr($GLOBALS['TL_LANGUAGE'], 0, 2);

            if (!file_exists(TL_ROOT . '/assets/tinymce/langs/' . $language . '.js')) {
                $language = 'en';
            }

            ob_start();
            // $selector and $language variables are used in the included file
            include TL_ROOT . '/system/config/' . $file . '.php';
            $updateMode = ob_get_contents();
            ob_end_clean();
        }

        return $updateMode;
    }
}
