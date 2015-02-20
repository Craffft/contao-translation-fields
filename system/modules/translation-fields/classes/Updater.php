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
 * Class Updater
 *
 * @copyright  Daniel Kiesel 2013-2014
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    translation-fields
 */
class Updater extends \Controller
{

	/**
	 * convertTranslationField function.
	 *
	 * @access public
	 * @static
	 * @param string $table
	 * @param string $field
	 * @return void
	 */
	public static function convertTranslationField($table, $field)
	{
		$backup = $field . '_backup';
		$objDatabase = \Database::getInstance();

		// Backup the original column and then change the column type
		if (!$objDatabase->fieldExists($backup, $table, true))
		{
			$objDatabase->query("ALTER TABLE `$table` ADD `$backup` text NULL");
			$objDatabase->query("UPDATE `$table` SET `$backup`=`$field`");
			$objDatabase->query("ALTER TABLE `$table` CHANGE `$field` `$field` int(10) unsigned NOT NULL default '0'");
			$objDatabase->query("UPDATE `$table` SET `$field`='0'");
		}


		$objRow = $objDatabase->query("SELECT id, $backup FROM $table WHERE $backup!=''");

		while ($objRow->next())
		{
			if (is_numeric($objRow->$backup))
			{
				$intFid = $objRow->$backup;
			}
			else if (strlen($objRow->$backup) > 0)
			{
				$intFid = \TranslationFieldsWidgetHelper::saveValuesAndReturnFid(\TranslationFieldsWidgetHelper::addValueToAllLanguages($objRow->$backup));
			}
			else
			{
				$intFid = 0;
			}

			$objDatabase->prepare("UPDATE $table SET $field=? WHERE id=?")
						->execute($intFid, $objRow->id);
		}
	}
}
