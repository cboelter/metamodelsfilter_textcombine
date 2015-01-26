<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package MetaModels
 * @subpackage FilterText
 * @author Christopher Boelter <christopher@boelter.eu>
 * @copyright The MetaModels team.
 * @license LGPL.
 * @filesource
 */

use MetaModels\Filter\Setting\Events\CreateFilterSettingFactoryEvent;
use MetaModels\Filter\Setting\TextCombineFilterSettingTypeFactory;
use MetaModels\MetaModelsEvents;
use MetaModels\DcGeneral\Events\Table\FilterSetting\TextCombine\Subscriber;
use MetaModels\Events\MetaModelsBootEvent;
use MetaModels\DcGeneral\Events\Table\FilterSetting\TextCombine\FilterSettingTypeRendererTextCombine;

return array
(
    MetaModelsEvents::SUBSYSTEM_BOOT_BACKEND => array(
        function (MetaModelsBootEvent $event) {
            new Subscriber($event->getServiceContainer());
            new FilterSettingTypeRendererTextCombine($event->getServiceContainer());
        }
    ),
    MetaModelsEvents::FILTER_SETTING_FACTORY_CREATE => array(
        function (CreateFilterSettingFactoryEvent $event) {
            $event->getFactory()->addTypeFactory(new TextCombineFilterSettingTypeFactory());
        }
    )
);