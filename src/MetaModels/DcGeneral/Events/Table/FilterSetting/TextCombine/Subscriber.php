<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package     AttributeTags
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @author      Christopher Boelter <christopher@boelter.eu>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Table\FilterSetting\TextCombine;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\IdSerializer;
use MetaModels\DcGeneral\Events\BaseSubscriber;

/**
 * Handle events for tl_metamodel_attribute for tag attributes.
 *
 * @package AttributeTags
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 */
class Subscriber extends BaseSubscriber
{
    /**
     * Boot the system in the backend.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function registerEventsInDispatcher()
    {
        $this
            ->addListener(GetPropertyOptionsEvent::NAME,
            array($this, 'getTableNames'))->addListener(DecodePropertyValueForWidgetEvent::NAME,
            array($this, 'decodeValue'))->addListener(EncodePropertyValueFromWidgetEvent::NAME,
            array($this, 'encodeValue'));
    }

    protected function getMetaModel($event)
    {
        $filterSetting = $this->getServiceContainer()->getFilterFactory()->createCollection($event->getModel()->getProperty('fid'));

        return $filterSetting->getMetaModel();
    }

    /**
     * Retrieve all database table names.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getTableNames(GetPropertyOptionsEvent $event)
    {

        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting') || ($event->getPropertyName() !== 'textcombine_attributes')) {
            return;
        }

        $metaModel = $this->getMetaModel($event);

        $knownAttributeTypes = $this->getServiceContainer()->getFilterFactory()->getTypeFactory('textcombine')->getKnownAttributeTypes();

        $result = array();

        foreach ($metaModel->getAttributes() as $attribute) {
            $typeName = $attribute->get('type');
            if ($knownAttributeTypes && (!in_array($typeName, $knownAttributeTypes))) {
                continue;
            }

            $strSelectVal          = $metaModel->getTableName() . '_' . $attribute->getColName();
            $result[$strSelectVal] = $attribute->getName() . ' [' . $typeName . ']';
        }

        $event->setOptions($result);
    }

    /**
     * Translates an attribute id to a generated alias {@see getAttributeNames()}.
     *
     * @param DecodePropertyValueForWidgetEvent $event The event.
     *
     * @return void
     */
    public function decodeValue(DecodePropertyValueForWidgetEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting') || ($event->getProperty() !== 'textcombine_attributes')) {
            return;
        }

        $metaModel = $this->getMetaModel($event);
        $values    = $event->getModel()->getProperty('textcombine_attributes');

        $attributes = array();
        if (!($metaModel || $values)) {
            return;
        }

        foreach ($values as $value) {
            $attribute = $metaModel->getAttributeById($value);
            if ($attribute) {
                $attributes[] = $metaModel->getTableName() . '_' . $attribute->getColName();
            }
        }

        if ($attribute) {
            $event->setValue($attributes);
        }
    }

    /**
     * Translates an generated alias {@see getAttributeNames()} to the corresponding attribute id.
     *
     * @param EncodePropertyValueFromWidgetEvent $event The event.
     *
     * @return void
     */
    public function encodeValue(EncodePropertyValueFromWidgetEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting') || ($event->getProperty() !== 'textcombine_attributes')) {
            return;
        }

        $metaModel = $this->getMetaModel($event);
        $values    = $event->getValue();

        $attributes = array();
        if (!($metaModel || $values)) {
            return;
        }

        foreach ($values as $value) {
            $value     = str_replace($metaModel->getTableName() . '_', '', $value);
            $attribute = $metaModel->getAttribute($value);
            if ($attribute) {
                $attributes[] = $attribute->get('id');
            }
        }

        if ($attributes) {
            $event->setValue($attributes);
        }
    }
}
