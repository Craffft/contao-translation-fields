<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package    TranslationFields
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2013-2014
 */


/**
 * Namespace
 */
namespace TranslationFields;


/**
 * Class TranslationFieldsBackendHelper
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <https://github.com/icodr8>
 * @package    translation-fields
 */
class TranslationFieldsBackendHelper extends \Backend
{

	/**
	 * copyDataRecord function.
	 *
	 * @access public
	 * @static
	 * @param int $intId
	 * @param \DataContainer $dc
	 * @return void
	 */
	public static function copyDataRecord($intId, \DataContainer $dc)
	{
		// If this is not the backend than return
		if (TL_MODE != 'BE')
		{
			return;
		}

		$strTable = $dc->table;
		$strModel = '\\' . \Model::getClassFromTable($strTable);
		$objTranslationController = new \TranslationController();

		// Return if the class does not exist (#9 thanks to tsarma)
		if (!class_exists($strModel))
		{
			return;
		}

		// Get object from model
		$objModel = $strModel::findByPk($intId);

		if ($objModel !== null)
		{
			$arrData = $objModel->row();

			if (is_array($arrData) && count($arrData) > 0)
			{
				// Load current data container
				$objTranslationController->loadDataContainer($strTable);

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
                                    $objCopy = clone $objTranslation->current();
                                    $objCopy->fid = $intFid;
                                    $objCopy->save();
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


	/**
	 * deleteDataRecord function.
	 *
	 * @access public
	 * @static
	 * @param int $intId
	 * @param \DataContainer $dc
	 * @return void
	 */
	public static function deleteDataRecord($dc)
	{
		// If this is not the backend than return
		if (TL_MODE != 'BE')
		{
			return;
		}

		// Check if there is an active record
		if ($dc instanceof \DataContainer && $dc->activeRecord)
		{
			$intId = $dc->activeRecord->id;

			$strTable = $dc->table;
			$strModel = '\\' . \Model::getClassFromTable($strTable);
			$objTranslationController = new \TranslationController();

			// Return if the class does not exist (#9 thanks to tsarma)
			if (!class_exists($strModel))
			{
				return;
			}

			// Get object from model
			$objModel = $strModel::findByPk($intId);

			if ($objModel !== null)
			{
				$arrData = $objModel->row();

				if (is_array($arrData) && count($arrData) > 0)
				{
					// Load current data container
					$objTranslationController->loadDataContainer($strTable);

                    // Get tl_undo data
                    $objUndo = \Database::getInstance()->prepare("SELECT * FROM tl_undo WHERE fromTable=? ORDER BY id DESC")->limit(1)->execute($dc->table);
                    $arrSet = $objUndo->row();

                    // Deserialize tl_undo data
                    $arrSet['data'] = deserialize($arrSet['data']);

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
									while ($objTranslation->next())
									{
                                        $t = \TranslationFieldsModel::getTable();

                                        // Add cross table record to undo data
                                        $arrSet['data'][$t][] = $objTranslation->row();

										// Delete translation
										$objTranslation->delete();
									}
								}
								break;
						}
					}

                    // Serialize tl_undo data
                    $arrSet['data'] = serialize($arrSet['data']);

                    // Update tl_undo
                    \Database::getInstance()->prepare("UPDATE tl_undo %s WHERE id=?")->set($arrSet)->execute($objUndo->id);
				}
			}
		}
	}
}
