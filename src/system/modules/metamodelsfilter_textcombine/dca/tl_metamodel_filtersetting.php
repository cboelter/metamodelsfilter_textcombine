<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage FilterTextCombine
 * @author     Christopher Bölter <c.boelter@cogizz.de>
 * @copyright  Cogizz - web solutions
 * @license    LGPL.
 * @filesource
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['metapalettes']['textcombine extends default'] = array
(
	'+config' => array('textcombine_attributes', 'textcombine_operator', 'urlparam', 'label', 'template', 'textsearch'),
);

/**
 * fields
 */
$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['textcombine_attributes'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_attributes'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('MetaModels\Dca\Filter', 'getAttributeNames'),
	'eval'                    => array(
		'doNotSaveEmpty'      => true,
		'alwaysSave'          => true,
		'submitOnChange'      => false,
		'includeBlankOption'  => true,
		'mandatory'           => true,
		'tl_class'            => 'long',
		'chosen'              => false,
		'multiple'			  => true
	),
	'load_callback'           => array(array('MetaModels\Dca\FilterTextCombine', 'attrIdToName')),
	'save_callback'           => array(array('MetaModels\Dca\FilterTextCombine', 'arrNameToAttrId')),
);


$GLOBALS['TL_DCA']['tl_metamodel_filtersetting']['fields']['textcombine_operator'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_operator'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'									=> array('or','and'),
	'reference'								=> $GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references'],
	'eval'                    => array(
		'doNotSaveEmpty'      => true,
		'alwaysSave'          => true,
		'submitOnChange'      => false,
		'includeBlankOption'  => false,
		'mandatory'           => false,
		'tl_class'            => 'w50 clr',
		'chosen'              => false,
		'multiple'						=> false
	),
);