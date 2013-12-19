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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'MetaModels\Filter\Setting\TextCombine' => 'system/modules/metamodelsfilter_textcombine/MetaModels/Filter/Setting/TextCombine.php',
	'MetaModels\Dca\FilterTextCombine' => 'system/modules/metamodelsfilter_textcombine/MetaModels/Dca/FilterTextCombine.php',
));