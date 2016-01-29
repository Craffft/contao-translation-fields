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

class TranslationFieldsPageModel extends \Model
{
    /**
     * @var string
     */
    protected static $strTable = 'tl_page';

    /**
     * @param array $arrOptions
     * @return mixed
     */
    public static function findRootPages(array $arrOptions = array())
    {
        $t = static::$strTable;
        $arrColumns = array("$t.type=?");
        $arrValues = array('root');

        if (!isset($arrOptions['order'])) {
            $arrOptions['order'] = "$t.fallback DESC, $t.sorting ASC";
        }

        return static::findBy($arrColumns, $arrValues, $arrOptions);
    }
}
