<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   translation_fields 
 * @author    Daniel Kiesel 
 * @license   LGPL 
 * @copyright Daniel Kiesel 2013 
 */


/**
 * Table tl_translation_fields 
 */
//dump($GLOBALS['TL_DCA']['tl_module']['fields']['name']);
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['inputType'] = 'languageText';
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['sql'] = 'blob NULL';
