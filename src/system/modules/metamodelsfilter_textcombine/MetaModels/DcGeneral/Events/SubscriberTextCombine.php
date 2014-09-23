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

namespace MetaModels\DcGeneral\Events;

use ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\BuildWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPasteButtonEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ManipulateWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\PostPersistModelEvent;
use ContaoCommunityAlliance\DcGeneral\Event\PreDeleteModelEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\Event\BuildDataDefinitionEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\Event\PopulateEnvironmentEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\Event\PreCreateDcGeneralEvent;
use MetaModels\DcGeneral\Dca\Builder\Builder;
use MetaModels\DcGeneral\Events\Table\InputScreen\PropertyPTable;
use MetaModels\DcGeneral\Events\Table\InputScreens\BuildPalette;
use MetaModels\DcGeneral\Events\Table\RenderSetting\RenderSettingBuildPalette;
use MetaModels\Factory;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetOperationButtonEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetGlobalButtonEvent;

/**
 * Event subscriber implementation for textcombine filter.
 *
 * @package MetaModels\DcGeneral\Events
 */
class SubscriberTextCombine extends BaseSubscriber
{
	/**
	 * Register all listeners to handle creation of a data container.
	 *
	 * @param CreateEventDispatcherEvent $event The event being processed.
	 *
	 * @return void
	 */
	public static function registerEvents(CreateEventDispatcherEvent $event)
	{
		$dispatcher = $event->getEventDispatcher();

		self::registerBuildDataDefinitionFor(
			'tl_metamodel_filtersetting',
			$dispatcher,
			__CLASS__ . '::registerTableMetaModelFilterSettingEvents'
		);
	}

	/**
	 * Register the additional events for table tl_metamodel_filtersetting.
	 *
	 * @return void
	 */
	public static function registerTableMetaModelFilterSettingEvents()
	{
		static $registered;
		if ($registered) {
			return;
		}
		$registered = true;
		$dispatcher = func_get_arg(2);

		self::registerListeners(
			array(
				GetPropertyOptionsEvent::NAME
				=> 'MetaModels\DcGeneral\Events\Table\FilterSetting\PropertyAttributeTextCombine::getOptions',
				DecodePropertyValueForWidgetEvent::NAME
				=> 'MetaModels\DcGeneral\Events\Table\FilterSetting\PropertyAttributeTextCombine::decodeValue',
				EncodePropertyValueFromWidgetEvent::NAME
				=> 'MetaModels\DcGeneral\Events\Table\FilterSetting\PropertyAttributeTextCombine::encodeValue'
			),
			$dispatcher,
			array('tl_metamodel_filtersetting', 'textcombine_attributes')
		);

		self::registerListeners(
			array(
				ModelToLabelEvent::NAME
				=> 'MetaModels\DcGeneral\Events\Table\FilterSetting\DrawTextCombineSetting::modelToLabelTextCombine',
			),
			$dispatcher,
			array('tl_metamodel_filtersetting')
		);
	}
}