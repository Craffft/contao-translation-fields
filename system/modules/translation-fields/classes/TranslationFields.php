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

class TranslationFields extends \Controller
{
    /**
     * TranslationFields constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $varValue
     * @param string $strForceLanguage
     * @return string
     */
    public static function translateValue($varValue, $strForceLanguage = '')
    {
        // Return value if it is already translated
        if (!is_numeric($varValue)) {
            return $varValue;
        }

        $arrLanguages = array();

        // If force language is set than add it as first language param
        if (strlen($strForceLanguage)) {
            $arrLanguages[] = $strForceLanguage;
        }

        // Add current langauge and default language to languages array
        $arrLanguages[] = $GLOBALS['TL_LANGUAGE'];
        $arrLanguages[] = 'en';

        // Get translation by current language and if it doesn't exist use the english translation
        foreach ($arrLanguages as $strLanguage) {
            $objTranslation = \TranslationFieldsModel::findOneByFidAndLanguage($varValue, $strLanguage);

            if ($objTranslation !== null) {
                return $objTranslation->content;
            }
        }

        // Get any translation
        $objTranslation = \TranslationFieldsModel::findOneByFid($varValue);

        if ($objTranslation !== null) {
            return $objTranslation->content;
        }

        return '';
    }

    /**
     * @param $strInputType
     * @param $varValue
     * @return array|mixed|string
     */
    protected static function translateField($strInputType, $varValue)
    {
        switch ($strInputType) {
            case 'TranslationInputUnit':
                $varValue = deserialize($varValue);

                if (is_array($varValue)) {
                    if (is_array($varValue['value']) && count($varValue['value']) > 0) {
                        $varValue['value'] = self::translateValue($varValue['value']);
                    }
                }
                break;

            case 'TranslationTextArea':
            case 'TranslationTextField':
                $varValue = self::translateValue($varValue);
                break;
        }

        return $varValue;
    }

    /**
     * @param \DataContainer $objDC
     * @return \DataContainer
     */
    public static function translateDCObject(\DataContainer $objDC)
    {
        // Get table
        $strTable = $objDC->current()->getTable();

        // Load current data container

        $objTranslationController = new \TranslationController();
        $objTranslationController->loadDataContainer($strTable);

        if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0) {
            foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues) {
                $objDC->$field = self::translateField($arrValues['inputType'], $objDC->$field);
            }
        }

        return $objDC;
    }

    /**
     * @param array $arrDC
     * @param $strTable
     * @return array
     */
    public static function translateDCArray(array $arrDC, $strTable)
    {
        if (count($GLOBALS['TL_DCA'][$strTable]['fields']) > 0) {
            foreach ($GLOBALS['TL_DCA'][$strTable]['fields'] as $field => $arrValues) {
                $arrDC[$field] = self::translateField($arrValues['inputType'], $arrDC[$field]);
            }
        }

        return $arrDC;
    }

    /**
     * @param $intFid
     * @return array
     */
    public static function getTranslationsByFid($intFid)
    {
        $arrData = array();

        // Get translation object
        $objTranslation = \TranslationFieldsModel::findByFid($intFid);

        if ($objTranslation !== null) {
            while ($objTranslation->next()) {
                $arrData[$objTranslation->language] = $objTranslation->content;
            }
        }

        return $arrData;
    }
}
