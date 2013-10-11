<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   TranslationFields
 * @author    Daniel Kiesel
 * @license   LGPL
 * @copyright Daniel Kiesel 2013
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFieldsCopyHelper
 *
 * @copyright  Daniel Kiesel 2013
 * @author     Daniel Kiesel
 * @package    translation_fields
 */
class TranslationFieldsCopyHelper extends \Backend
{

	/**
	 * copyDataRecord function.
	 *
	 * @access public
	 * @static
	 * @param int $intCopyId
	 * @param \DataContainer $dc
	 * @return void
	 */
	public static function copyDataRecord($intCopyId, \DataContainer $dc)
	{
		$strTable = $dc->table;
		$strModel = '\\' . \System::getModelClassFromTable($strTable);

		// Get object from model
		$objModel = $strModel::findByPk($intCopyId);

		if ($objModel !== null)
		{
			$arrData = $objModel->row();

			if (is_array($arrData) && count($arrData) > 0)
			{
				// Load current DCA
				// DOTO

				foreach ($arrData as $strField => $varValue)
				{
					switch ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'])
					{
						case 'TranslationInputUnit':
						case 'TranslationTextArea':
						case 'TranslationTextField':
							// Get translation values
							$objTranslation = \TranslationFieldsModel::findByFid($varValue);

							if ($objTranslation !== null)
							{
								// Get next fid
								$intFid = \TranslationFieldsModel::getNextFid();

								// Set copy fid by field
								$objModel->$strField = $intFid;

								while ($objTranslation->next())
								{
									// Generate new translation fields object to copy the current
									$objCopyTranslation = new \TranslationFieldsModel();
									$objCopyTranslation->setRow($objTranslation->row());
									$objCopyTranslation->id = null;
									$objCopyTranslation->fid = $intFid;
									$objCopyTranslation->save();
								}
							}
							break;
					}
				}
			}

			// Save model object
			$objModel->save();
		}
	}
}