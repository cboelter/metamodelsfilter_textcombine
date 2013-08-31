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
 * Frontend filter
 */
$GLOBALS['METAMODELS']['filters']['textcombine'] = array
(
	'class' => 'MetaModelFilterSettingTextCombine',
	'attr_filter' => array('text','longtext'),
	'image' => 'system/modules/metamodelsfilter_text/html/filter_text.png',
	'info_callback' => array('TableMetaModelFilterSetting_TextCombine','infoCallback')
);