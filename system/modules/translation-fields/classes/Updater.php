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

class Updater extends \Controller
{
    /**
     * @param $table
     * @param $field
     */
    public static function convertTranslationField($table, $field)
    {
        $backup = $field . '_backup';
        $objDatabase = \Database::getInstance();

        // Backup the original column and then change the column type
        if (!$objDatabase->fieldExists($backup, $table, true)) {
            $objDatabase->query("ALTER TABLE `$table` ADD `$backup` text NULL");
            $objDatabase->query("UPDATE `$table` SET `$backup`=`$field`");
            $objDatabase->query("ALTER TABLE `$table` CHANGE `$field` `$field` int(10) unsigned NOT NULL default '0'");
            $objDatabase->query("UPDATE `$table` SET `$field`='0'");
        }

        $objRow = $objDatabase->query("SELECT id, $backup FROM $table WHERE $backup!=''");

        while ($objRow->next()) {
            if (is_numeric($objRow->$backup)) {
                $intFid = $objRow->$backup;
            } else {
                if (strlen($objRow->$backup) > 0) {
                    $intFid = \TranslationFieldsWidgetHelper::saveValuesAndReturnFid(\TranslationFieldsWidgetHelper::addValueToAllLanguages($objRow->$backup));
                } else {
                    $intFid = 0;
                }
            }

            $objDatabase
                ->prepare("UPDATE $table SET $field=? WHERE id=?")
                ->execute($intFid, $objRow->id);
        }
    }
}
