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
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typenames']['textcombine']     = 'Kombinierter-Textfilter';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typedesc']['textcombine']  = '%s <strong>%s</strong> %s <br /> für Attribute <em>%s</em>';

/**
 * fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textsearch']   = array('Suchart', 'Teiltext-Findung bei Textsuche.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_attributes'] =
    array('Suchfelder', 'Durchsuchbare Attribute.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['textcombine_operator'] =
    array('Verknüpfung der Suchfelder', 'Art der Verknüpfung für die Suchfelder.');
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['label_as_placeholder'] =
    array('Label als Platzhalter', 'Das Label als HTML5-Placeholder nutzen.');

/**
 * references
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['exact']      	= 'exakte Suche';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['beginswith'] 	= 'beginnt mit Suchtext';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['endswith']   	= 'endet mit Suchtext';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['or']   		= 'oder';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['references']['and']   		= 'und';