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

class TranslationFieldsModel extends \Model
{
    protected static $strTable = 'tl_translation_fields';

    /**
     * @param $intFid
     * @param $strLanguage
     * @param array $arrOptions
     * @return mixed
     */
    public static function findOneByFidAndLanguage($intFid, $strLanguage, array $arrOptions = array())
    {
        $t = static::$strTable;

        $arrColumns = array("$t.fid=? AND $t.language=?");
        $arrValues = array($intFid, $strLanguage);

        return static::findOneBy($arrColumns, $arrValues, $arrOptions);
    }

    /**
     * @return int|mixed|null
     */
    public static function getNextFid()
    {
        $t = static::$strTable;

        $intFid = \Database::getInstance()->prepare("SELECT (fid + 1) AS nextFid FROM $t ORDER BY fid DESC")->limit(1)->execute()->nextFid;
        $intFid = ($intFid === null) ? 1 : $intFid;

        return $intFid;
    }
}
