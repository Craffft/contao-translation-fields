<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package translation_fields
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
	'TranslationFields\Frontend'          => 'system/modules/translation_fields/classes/Frontend.php',
	'TranslationFields\TranslationFields'    => 'system/modules/translation_fields/classes/TranslationFields.php',
	'TranslationFields\Module'            => 'system/modules/translation_fields/classes/Module.php',

	// Models
	'TranslationFields\PageModel'         => 'system/modules/translation_fields/models/PageModel.php',

	// Widgets
	'TranslationFields\LanguageTextField' => 'system/modules/translation_fields/widgets/LanguageTextField.php',
));
