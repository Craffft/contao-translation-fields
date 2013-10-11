<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
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
	'TranslationFields\TranslationFields'             => 'system/modules/translation_fields/classes/TranslationFields.php',
	'TranslationFields\TranslationFieldsCopyHelper'   => 'system/modules/translation_fields/classes/TranslationFieldsCopyHelper.php',
	'TranslationFields\TranslationFieldsWidgetHelper' => 'system/modules/translation_fields/classes/TranslationFieldsWidgetHelper.php',

	// Driver
	'TranslationFields\DC_Table'                      => 'system/modules/translation_fields/driver/DC_Table.php',

	// Models
	'TranslationFields\PageModel'                     => 'system/modules/translation_fields/models/PageModel.php',
	'TranslationFields\TranslationFieldsModel'        => 'system/modules/translation_fields/models/TranslationFieldsModel.php',

	// Widgets
	'TranslationFields\TranslationInputUnit'          => 'system/modules/translation_fields/widgets/TranslationInputUnit.php',
	'TranslationFields\TranslationTextArea'           => 'system/modules/translation_fields/widgets/TranslationTextArea.php',
	'TranslationFields\TranslationTextField'          => 'system/modules/translation_fields/widgets/TranslationTextField.php',
));
