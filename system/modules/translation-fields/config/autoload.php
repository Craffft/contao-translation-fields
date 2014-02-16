<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package translation-fields
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
	'TranslationFields\TranslationController'          => 'system/modules/translation-fields/classes/TranslationController.php',
	'TranslationFields\TranslationFields'              => 'system/modules/translation-fields/classes/TranslationFields.php',
	'TranslationFields\TranslationFieldsBackendHelper' => 'system/modules/translation-fields/classes/TranslationFieldsBackendHelper.php',
	'TranslationFields\TranslationFieldsWidgetHelper'  => 'system/modules/translation-fields/classes/TranslationFieldsWidgetHelper.php',
	'TranslationFields\Updater'                        => 'system/modules/translation-fields/classes/Updater.php',

	// Driver
	'TranslationFields\DC_Table'                       => 'system/modules/translation-fields/driver/DC_Table.php',

	// Models
	'TranslationFields\TranslationFieldsModel'         => 'system/modules/translation-fields/models/TranslationFieldsModel.php',
	'TranslationFields\TranslationFieldsPageModel'     => 'system/modules/translation-fields/models/TranslationFieldsPageModel.php',

	// Widgets
	'TranslationFields\TranslationInputUnit'           => 'system/modules/translation-fields/widgets/TranslationInputUnit.php',
	'TranslationFields\TranslationTextArea'            => 'system/modules/translation-fields/widgets/TranslationTextArea.php',
	'TranslationFields\TranslationTextField'           => 'system/modules/translation-fields/widgets/TranslationTextField.php',
));
