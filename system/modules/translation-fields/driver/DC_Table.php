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

class DC_Table extends \Contao\DC_Table
{
    /**
     * @param bool $blnDoNotRedirect
     * @return bool|int
     */
    public function copy($blnDoNotRedirect = false)
    {
        // Define oncopy callback for every copy
        $GLOBALS['TL_DCA'][$this->strTable]['config']['oncopy_callback'][] = array(
            '\TranslationFields\TranslationFieldsBackendHelper',
            'copyDataRecord'
        );

        return parent::copy($blnDoNotRedirect);
    }

    /**
     * @param bool $blnDoNotRedirect
     */
    public function delete($blnDoNotRedirect = false)
    {
        // Define ondelete callback for every deltion
        $GLOBALS['TL_DCA'][$this->strTable]['config']['ondelete_callback'][] = array(
            '\TranslationFields\TranslationFieldsBackendHelper',
            'deleteDataRecord'
        );

        // Call parent
        parent::delete($blnDoNotRedirect);
    }
}
