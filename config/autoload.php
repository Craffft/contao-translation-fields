<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Language_fields
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'LanguageFields',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'LanguageFields\LanguageFields'    => 'system/modules/language_fields/classes/LanguageFields.php',

	// Models
	'LanguageFields\PageModel'         => 'system/modules/language_fields/models/PageModel.php',

	// Widgets
	'LanguageFields\LanguageTextField' => 'system/modules/language_fields/widgets/LanguageTextField.php',
));
