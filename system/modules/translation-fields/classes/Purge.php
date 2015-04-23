<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    TranslationFields
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2014
 */

/**
 * Namespace
 */
namespace TranslationFields;

/**
 * Class Purge
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class Purge extends \Controller
{
    public function purgeTranslationFields()
    {
        $database = \Database::getInstance();
        $arrStructure = $this->listAllTranslationFields();
        $arrFids = $this->getUsedTranslationFieldIds($arrStructure);

        if (is_array($arrFids) && !empty($arrFids)) {
            $database->prepare(sprintf("DELETE FROM tl_translation_fields WHERE fid NOT IN(%s)",
                implode(',', array_map('intval', $arrFids))
            ))->execute();
        }
    }

    protected function getUsedTranslationFieldIds($arrStructure)
    {
        $database = \Database::getInstance();
        $arrFids = array();

        // Get used field ids
        if (is_array($arrStructure)) {
            foreach ($arrStructure as $strTable => $arrFields) {
                if (is_array($arrFields) && !empty($arrFields)) {
                    switch ($GLOBALS['TL_DCA'][$strTable]['config']['dataContainer']) {
                        case 'Table':
                            $objData = $database->prepare(sprintf("SELECT %s FROM %s",
                                implode(', ', $arrFields),
                                $strTable
                            ))->execute();

                            if ($objData !== null) {
                                foreach ($arrFields as $strField) {
                                    $arrFids = array_merge($arrFids, $objData->fetchEach($strField));
                                }
                            }
                            break;

                        case 'File':
                            foreach ($arrFields as $strField) {
                                $arrFids[] = ($GLOBALS['TL_CONFIG'][$strField]);
                            }
                            break;
                    }
                }
            }

            $arrFids = array_diff($arrFids, array(0));
            $arrFids = array_unique($arrFids);
        }

        return $arrFids;
    }

    protected function listAllTranslationFields()
    {
        $arrStructure = array();
        $arrFiles = $this->listDataContainerArrayFiles();

        if (is_array($arrFiles)) {
            foreach ($arrFiles as $strFile) {
                // Load data container
                $this->loadDataContainer($strFile);

                $arrFields = &$GLOBALS['TL_DCA'][$strFile]['fields'];

                foreach ($arrFields as $strField => $varValue) {
                    switch ($varValue['inputType']) {
                        case 'TranslationInputUnit':
                        case 'TranslationTextArea':
                        case 'TranslationTextField':
                            $arrStructure[$strFile][] = $strField;
                            break;
                    }
                }
            }
        }

        return $arrStructure;
    }

    protected function listDataContainerArrayFiles()
    {
        $arrFiles = array();

        // Parse all active modules
        foreach (\ModuleLoader::getActive() as $strModule) {
            $strDir = 'system/modules/' . $strModule . '/dca';

            if (!is_dir(TL_ROOT . '/' . $strDir)) {
                continue;
            }

            foreach (scan(TL_ROOT . '/' . $strDir) as $strFile) {
                // Ignore non PHP files and files which have been included before
                if (strncmp($strFile, '.', 1) === 0
                    ||
                    substr($strFile, -4) != '.php'
                    ||
                    in_array($strFile, $arrFiles)
                ) {
                    continue;
                }

                $arrFiles[] = substr($strFile, 0, -4);
            }
        }

        return $arrFiles;
    }
}
