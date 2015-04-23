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
 * Class TranslationFields
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class DC_Table extends \Contao\DC_Table
{
    /**
     * copy function.
     *
     * @access public
     * @param bool $blnDoNotRedirect (default: false)
     * @return bool
     */
    public function copy($blnDoNotRedirect = false)
    {
        // Define oncopy callback for every copy
        $GLOBALS['TL_DCA'][$this->strTable]['config']['oncopy_callback'][] = array(
            '\TranslationFields\TranslationFieldsBackendHelper',
            'copyDataRecord'
        );

        // Return parent copy
        return parent::copy($blnDoNotRedirect);
    }

    /**
     * delete function.
     *
     * @access public
     * @param bool $blnDoNotRedirect (default: false)
     * @return void
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
