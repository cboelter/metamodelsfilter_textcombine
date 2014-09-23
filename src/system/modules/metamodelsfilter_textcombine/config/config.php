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
 * @copyright  cogizz - digital communications
 * @license    LGPL.
 * @filesource
 */

/**
 * Frontend filter
 */
$GLOBALS['METAMODELS']['filters']['textcombine']['class'] = 'MetaModels\Filter\Setting\TextCombine';
$GLOBALS['METAMODELS']['filters']['textcombine']['image'] = 'system/modules/metamodelsfilter_textcombine/html/filter_text.png';
$GLOBALS['METAMODELS']['filters']['textcombine']['attr_filter'][] = 'text';
$GLOBALS['METAMODELS']['filters']['textcombine']['attr_filter'][] = 'longtext';
$GLOBALS['METAMODELS']['filters']['textcombine']['attr_filter'][] = 'translatedlongtext';
$GLOBALS['METAMODELS']['filters']['textcombine']['attr_filter'][] = 'translatedtext';

/**
 * Register events
 */
$GLOBALS['TL_EVENTS'][\ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent::NAME][] =
	'MetaModels\DcGeneral\Events\SubscriberTextCombine::registerEvents';