<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage FilterTextCombine
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * filter types
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typenames']['textcombine'] = 'Combine-Text filter';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['textcombine']  =
    '%s <strong>%s</strong> %s <br /> for attribute <em>%s</em>';

/**
 * fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textsearch']             =
    array('Search type', 'Finding text parts.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_attributes'] =
    array('Searchfields', 'Fields that will be included in the search.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_operator']   =
    array('Operator', 'The Operator between the searchfields.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['label_as_placeholder']   =
    array('Label as placeholder', 'Using the label as HTML5-Placeholder.');

/**
 * references
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['exact']      = 'exact search';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['beginswith'] = 'begins with search term';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['endswith']   = 'ends with search term';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['or']         = 'or';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['and']        = 'and';