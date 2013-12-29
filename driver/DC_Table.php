<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    TranslationFields
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFields
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @package    translation_fields
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
	public function copy($blnDoNotRedirect=false)
	{
		// Define oncopy callback for every copy
		$GLOBALS['TL_DCA'][$this->strTable]['config']['oncopy_callback'][] = array('\TranslationFields\TranslationFieldsBackendHelper', 'copyDataRecord');

		// Return parent copy
		return parent::copy($blnDoNotRedirect);
	}
}
