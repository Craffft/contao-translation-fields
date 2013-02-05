<?php 

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package   language_fields 
 * @author    Daniel Kiesel 
 * @license   LGPL 
 * @copyright Daniel Kiesel 2013 
 */


/**
 * Table tl_language_fields 
 */
$GLOBALS['TL_DCA']['tl_language_fields'] = array
(

	// Config
	'config' => array
	(
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'language' => array
		(
			'sql'                     => "varchar(2) NOT NULL default ''"
		),
		'table' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'field' => array
		(
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'data' => array
		(
			'sql'                     => "text NULL"
		)
	)
);
