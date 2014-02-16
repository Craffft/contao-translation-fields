Contao extension Translation Fields
===================================

What is Translation Fields?
---------------------------

Translation Fields is a library for Contao developers to get nice translation fields in the Contao Open Source CMS.
Every translation field gets a language flag and can be translated by changing the flag to another language. The translations will be saved in the table __tl_translation_fields__ and a key from this table will be stored in the field self.

Dependencies
------------

- none

Troubleshooting
---------------

Directly on github! See https://github.com/iCodr8/contao-translation-fields/issues

Documentation
=============

Input types
-----------

There are three input types that you can use in the back end.
- __TranslationTextField__ (the same as input type __text__)
- __TranslationTextArea__ (the same as input type __textarea__)
- __TranslationInputType__ (the same as input type __inputType__)

How to define a field in the DCA
--------------------------------

To use the translation fields, you have to do the following changes in your DCA code.
- Add an index to your field
- Change the input type
- Change the sql to int(10)
- Add a relation to your field

Each field uses different settings. You can see this in the following codes.

### Examples ###
#### Text Field ####
The original field:

    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('maxlength'=>255),
        'sql'                     => "varchar(255) NOT NULL default ''"
    );

The field after the changes:

    $GLOBALS['TL_DCA']['tl_mytable']['config']['sql']['keys']['myfield'] = 'index';
    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'TranslationTextField',
        'eval'                    => array('maxlength'=>255),
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
    );


#### Textarea Field ####
The original field:

    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'textarea',
        'eval'                    => array('rte'=>'tinyFlash', 'tl_class'=>'long'),
        'sql'                     => "text NULL"
    );

The field after the changes:

    $GLOBALS['TL_DCA']['tl_mytable']['config']['sql']['keys']['myfield'] = 'index';
    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'TranslationTextArea',
        'eval'                    => array('rte'=>'tinyFlash', 'tl_class'=>'long'),
        'sql'                     => "int(10) unsigned NOT NULL default '0'",
        'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
    );


#### Input Unit Field ####
The original field:

    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'inputUnit',
        'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
        'eval'                    => array('maxlength'=>200, 'tl_class'=>'w50'),
        'sql'                     => "blob NULL"
    );

The field after the changes:

    $GLOBALS['TL_DCA']['tl_mytable']['config']['sql']['keys']['myfield'] = 'index';
    $GLOBALS['TL_DCA']['tl_mytable']['fields']['myfield'] = array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_mytable']['myfield'],
        'exclude'                 => true,
        'inputType'               => 'TranslationInputUnit',
        'options'                 => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
        'eval'                    => array('maxlength'=>200, 'tl_class'=>'w50'),
        'sql'                     => "blob NULL",
        'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
    );


How to translate the field values
---------------------------------

To translate the key from your current field, you can use the following methods

### Translate value ###
Translates the field key to the translation value in the current language.

    $intId = '1485'; // Example value

    $strTranslated = \TranslationFields::translateValue($intId);

    echo $strTranslated; // Returns e.g. "Hi there!"

Optional you can add a force language to the translateValue method.

    $intId = '1485'; // Example value
    $strForceLanguage = 'de';

    $strTranslated = \TranslationFields::translateValue($intId, $strForceLanguage);

    echo $strTranslated; // Returns e.g. "Hallo zusammen!"


### Translate DataContainer object ###
Translates all translation field values in the data container object to a translated value.

    $objDC->exampleValue = '1485'; // Example value

    $objDC = \TranslationFields::translateDCObject($objDC);

    echo $objDC->exampleValue; // Returns e.g. "Hi there!"


### Translate DCA ###
Translates all translation field values in the data container array to a translated value.

    $arrDC['exampleValue'] = '1485'; // Example value

    $arrDC = \TranslationFields::translateDCArray($arrDC, $strTable);

    echo $arrDC['exampleValue']; // Returns e.g. "Hi there!"


Runonce
-------

If you already have content in your application fields, you have to ensure that translation fields doesn't remove your content data. Therefore you have to create a runonce which inserts the current values into the __tl_translation_fields__ table and associate the key with the field.

You can do this like in the following code:

    class MyApplicationRunconce extends \Controller
    {
        // Code ...

        public function __construct()
        {
            parent::__construct();

            // Code ...

            // Load required translation-fields classes
            \ClassLoader::addNamespace('TranslationFields');
            \ClassLoader::addClass('TranslationFields\Updater', 'system/modules/translation-fields/classes/Updater.php');
            \ClassLoader::addClass('TranslationFields\TranslationFieldsWidgetHelper', 'system/modules/translation-fields/classes/TranslationFieldsWidgetHelper.php');
            \ClassLoader::addClass('TranslationFields\TranslationFieldsModel', 'system/modules/translation-fields/models/TranslationFieldsModel.php');
            \ClassLoader::register();
        }


        public function run()
        {
            // Code ...

            \TranslationFields\Updater::convertTranslationField('tl_my_table_name', 'my_field_name');

            // Code ...
        }

        // Code ...
    }

E.g. you can have a look at the runconce.php from my extension Photoalbums2:
https://github.com/iCodr8/contao-photoalbums2/blob/master/config/runonce.php
