<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    TranslationFields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2016
 */

/**
 * Namespace
 */
namespace TranslationFields;

/**
 * Class TranslationFieldsWidgetHelper
 *
 * @copyright  Daniel Kiesel 2013-2016
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class TranslationFieldsWidgetHelper extends \Backend
{
    /**
     * arrLng
     *
     * (default value: array())
     *
     * @var array
     * @access private
     * @static
     */
    private static $arrLng = array();

    /**
     * addFallbackValueToEmptyField function.
     *
     * @access public
     * @static
     * @param mixed $varInput
     * @return mixed
     */
    public static function addFallbackValueToEmptyField($varInput)
    {
        if (is_array($varInput)) {
            // Add fallback text to other languages
            if (count($varInput) > 0) {
                $strFallbackValue = $varInput[key($varInput)];

                foreach ($varInput as $key => $value) {
                    if (strlen($value) < 1) {
                        $varInput[$key] = $strFallbackValue;
                    }
                }
            }
        }

        return $varInput;
    }

    /**
     * addValueToAllLanguages function.
     *
     * @access public
     * @static
     * @param string $strValue
     * @return array
     */
    public static function addValueToAllLanguages($strValue)
    {
        $arrData = self::getEmptyTranslationLanguages();

        if (is_array($arrData) && count($arrData) > 0) {
            foreach ($arrData as $k => $v) {
                $arrData[$k] = $strValue;
            }
        }

        return $arrData;
    }

    /**
     * saveValuesAndReturnFid function.
     *
     * @access public
     * @static
     * @param array $arrValues
     * @param int $intFid (default: null)
     * @return int
     */
    public static function saveValuesAndReturnFid($arrValues, $intFid = null)
    {
        $arrLanguages = self::getTranslationLanguageKeys();

        // Check if translation fields should not be empty saved
        if (!$GLOBALS['TL_CONFIG']['dontfillEmptyTranslationFields']) {
            // Add fallback text to empty values
            $arrValues = self::addFallbackValueToEmptyField($arrValues);
        }

        if (is_array($arrLanguages) && count($arrLanguages)) {
            foreach ($arrLanguages as $strLanguage) {
                // If current fid is correct
                if (is_numeric($intFid) && $intFid > 0) {
                    // Get existing translation object by fid
                    $objTranslation = \TranslationFieldsModel::findOneByFidAndLanguage($intFid, $strLanguage);

                    // Get new translation object by fid
                    if ($objTranslation === null) {
                        // Create translation object
                        $objTranslation = new \TranslationFieldsModel();
                        $objTranslation->language = $strLanguage;
                        $objTranslation->fid = $intFid;
                    }
                }

                // Get new translation object with new fid
                if ($objTranslation === null) {
                    // Get next fid
                    $intFid = \TranslationFieldsModel::getNextFid();

                    // Create translation object
                    $objTranslation = new \TranslationFieldsModel();
                    $objTranslation->language = $strLanguage;
                    $objTranslation->fid = $intFid;
                }

                // Set content value
                $objTranslation->content = $arrValues[$strLanguage];

                // Set current timestamp
                $objTranslation->tstamp = time();

                // Save
                $objTranslation->save();
            }
        }

        return $intFid;
    }

    /**
     * getTranslationsByFid function.
     *
     * @access public
     * @static
     * @param int $intFid
     * @return array
     */
    public static function getTranslationsByFid($intFid, $onlyActiveLanguages = false)
    {
        // Get empty tranlation languages
        $arrData = self::getEmptyTranslationLanguages();

        if (is_numeric($intFid) && $intFid > 0) {
            $objTranslation = \TranslationFieldsModel::findByFid($intFid);

            if ($objTranslation !== null) {
                while ($objTranslation->next()) {
                    $arrData[$objTranslation->language] = $objTranslation->content;
                }
            }
        }

        // If only active languages should be returned
        if ($onlyActiveLanguages) {
            $arrActiveData = array();
            $arrKeys = self::getTranslationLanguageKeys();

            if (is_array($arrKeys) && count($arrKeys) > 0) {
                foreach ($arrKeys as $key) {
                    $arrActiveData[$key] = (!isset($arrData[$key]) ? '' : $arrData[$key]);
                }
            }

            // Replace data with active data
            $arrData = $arrActiveData;
        }

        // Return data array
        return $arrData;
    }

    /**
     * setTranslationLanguages function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function setTranslationLanguages()
    {
        // Get all languages
        $arrLanguages = \System::getLanguages();

        // Get all used languages
        $arrLng = array();

        // If languages are specified
        if ($GLOBALS['TL_CONFIG']['chooseTranslationLanguages'] == '1') {
            $arrTranslationLanguages = deserialize($GLOBALS['TL_CONFIG']['translationLanguages']);

            if (is_array($arrTranslationLanguages) && $arrTranslationLanguages > 0) {
                foreach ($arrTranslationLanguages as $strLng) {
                    $arrLng[$strLng] = $arrLanguages[$strLng];
                }
            }
        } else {
            $objRootPages = \TranslationFieldsPageModel::findRootPages();

            if ($objRootPages !== null) {
                while ($objRootPages->next()) {
                    $arrLng[$objRootPages->language] = $arrLanguages[$objRootPages->language];
                }
            }

            // If langauge array is empty
            if (count($arrLng) < 1) {
                // Set all available languages
                $arrLng = \System::getLanguages(true);

                // Set the language of the user to the top
                if (\BackendUser::getInstance()->language != null) {
                    // Get langauge value
                    $strLngValue = $arrLng[\BackendUser::getInstance()->language];

                    // Remove the current language from the array
                    unset($arrLng[\BackendUser::getInstance()->language]);

                    // Add old array to a temp array
                    $arrLngTemp = $arrLng;

                    // Generate a new array
                    $arrLng = array(\BackendUser::getInstance()->language => $strLngValue);

                    // Merge the old array into the new array
                    $arrLng = array_merge($arrLng, $arrLngTemp);
                }
            }
        }

        self::$arrLng = $arrLng;
    }

    /**
     * getTranslationLanguages function.
     *
     * @access public
     * @static
     * @param bool $blnReload (default: false)
     * @return array
     */
    public static function getTranslationLanguages($blnReload = false)
    {
        if ($blnReload || !is_array(self::$arrLng) || count(self::$arrLng) < 1) {
            self::setTranslationLanguages();
        }

        return self::$arrLng;
    }

    /**
     * getEmptyTranslationLanguages function.
     *
     * @access public
     * @static
     * @param bool $blnReload (default: false)
     * @return array
     */
    public static function getTranslationLanguageKeys($blnReload = false)
    {
        $arrLng = self::getTranslationLanguages($blnReload);

        return array_keys($arrLng);
    }

    /**
     * getEmptyTranslationLanguages function.
     *
     * @access public
     * @static
     * @param bool $blnReload (default: false)
     * @return array
     */
    public static function getEmptyTranslationLanguages($blnReload = false)
    {
        $arrLng = self::getTranslationLanguages($blnReload);

        foreach ($arrLng as $k => $v) {
            $arrLng[$k] = '';
        }

        return $arrLng;
    }

    /**
     * getInputTranslationLanguages function.
     *
     * @access public
     * @static
     * @param array $varValue
     * @param bool $blnReload (default: false)
     * @return array
     */
    public static function getInputTranslationLanguages($varValue, $blnReload = false)
    {
        if (!is_array($varValue)) {
            $varValue = array();
        }

        // Be sure that translation languages are loaded
        self::getTranslationLanguages($blnReload);

        // Set new inputs array
        $arrLngInputs = self::$arrLng;

        // Merge value array languages into inputs array
        /*if (count($varValue) > 0)
        {
            $arrLngInputs = array_merge($arrLngInputs, $varValue);
        }*/

        // Get array keys
        $arrLngInputs = array_keys($arrLngInputs);

        return $arrLngInputs;
    }

    /**
     * getCurrentTranslationLanguageButton function.
     *
     * @access public
     * @static
     * @return string
     */
    public static function getCurrentTranslationLanguageButton()
    {
        // Get current translation languages
        $arrLngKeys = array_keys(self::$arrLng);
        $strFlagname = (strtolower(strlen($arrLngKeys[0]) > 2 ? substr($arrLngKeys[0], -2) : $arrLngKeys[0]));

        // Set empty flagname, if flag doesn't exist
        if (!file_exists(TL_ROOT . '/' . sprintf('system/modules/translation-fields/assets/images/flag_icons/%s.png',
                $strFlagname))
        ) {
            $strFlagname = 'xx';
        }

        // Generate current translation language button
        $strButton = sprintf('<span class="tf_button"><img src="system/modules/translation-fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s"></span>',
            $strFlagname,
            self::$arrLng[$arrLngKeys[0]]);

        return $strButton;
    }

    /**
     * getTranslationLanguagesList function.
     *
     * @access public
     * @static
     * @param string $strId
     * @param array $varValue
     * @return string
     */
    public static function getTranslationLanguagesList($varValue)
    {
        if (!is_array($varValue)) {
            $varValue = array();
        }

        // Generate langauge list
        $arrLngList = array();
        $i = 0;

        foreach (self::$arrLng as $key => $value) {
            $strFlagname = (strtolower(strlen($key) > 2 ? substr($key, -2) : $key));

            // Set empty flagname, if flag doesn't exist
            if (!file_exists(TL_ROOT . '/' . sprintf('system/modules/translation-fields/assets/images/flag_icons/%s.png',
                    $strFlagname))
            ) {
                $strFlagname = 'xx';
            }

            $strLngIcon = sprintf('<img src="system/modules/translation-fields/assets/images/flag_icons/%s.png" width="16" height="11" alt="%s">',
                $strFlagname,
                $value);

            $arrLngList[] = sprintf('<li id="lng_list_item_%s" class="tf_lng_item%s">%s%s</li>',
                $key,
                (strlen(specialchars(@$varValue[$key])) > 0) ? ' translated' : '',
                $strLngIcon,
                $value);
            $i++;
        }

        $strLngList = sprintf('<ul class="tf_lng_list">%s</ul>',
            implode(' ', $arrLngList));

        return $strLngList;
    }
}
