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
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['inputType'] = 'TranslationTextField';
$GLOBALS['TL_DCA']['tl_module']['fields']['name']['sql'] = "int(10) unsigned NOT NULL default '0'";
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['inputType'] = 'TranslationInputUnit';
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['sql'] = "blob NULL";
$GLOBALS['TL_DCA']['tl_module']['fields']['pa2Teaser']['inputType'] = 'TranslationTextArea';
$GLOBALS['TL_DCA']['tl_module']['fields']['pa2Teaser']['sql'] = "int(10) unsigned NOT NULL default '0'";
