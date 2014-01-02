<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Translation_fields
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'TranslationFields',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'TranslationFields\TranslationController'          => 'system/modules/translation_fields/classes/TranslationController.php',
	'TranslationFields\TranslationFields'              => 'system/modules/translation_fields/classes/TranslationFields.php',
	'TranslationFields\TranslationFieldsBackendHelper' => 'system/modules/translation_fields/classes/TranslationFieldsBackendHelper.php',
	'TranslationFields\TranslationFieldsWidgetHelper'  => 'system/modules/translation_fields/classes/TranslationFieldsWidgetHelper.php',
	'TranslationFields\Updater'                        => 'system/modules/translation_fields/classes/Updater.php',

	// Driver
	'TranslationFields\DC_Table'                       => 'system/modules/translation_fields/driver/DC_Table.php',

	// Models
	'TranslationFields\TranslationFieldsModel'         => 'system/modules/translation_fields/models/TranslationFieldsModel.php',
	'TranslationFields\TranslationFieldsPageModel'     => 'system/modules/translation_fields/models/TranslationFieldsPageModel.php',

	// Widgets
	'TranslationFields\TranslationInputUnit'           => 'system/modules/translation_fields/widgets/TranslationInputUnit.php',
	'TranslationFields\TranslationTextArea'            => 'system/modules/translation_fields/widgets/TranslationTextArea.php',
	'TranslationFields\TranslationTextField'           => 'system/modules/translation_fields/widgets/TranslationTextField.php',
));
