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

namespace MetaModels\DcGeneral\Events\Table\FilterSetting;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\DecodePropertyValueForWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\EncodePropertyValueFromWidgetEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use MetaModels\Filter\Setting\Factory as FilterFactory;

/**
 * Event handlers for tl_metamodel_filtersetting.textcombine_attributes.
 *
 * @package MetaModels\DcGeneral\Events\Table\FilterSetting
 */
class PropertyAttributeTextCombine
{
	/**
	 * Retrieve the MetaModel attached to the model filter setting.
	 *
	 * @param ModelInterface $model The model for which to retrieve the MetaModel.
	 *
	 * @return \MetaModels\IMetaModel
	 */
	public static function getMetaModel(ModelInterface $model)
	{
		$filterSetting = FilterFactory::byId($model->getProperty('fid'));

		return $filterSetting->getMetaModel();
	}

	/**
	 * Prepares a option list with alias => name connection for all attributes.
	 *
	 * This is used in the textcombine_attributes select box.
	 *
	 * @param GetPropertyOptionsEvent $event The event.
	 *
	 * @return void
	 */
	public static function getOptions(GetPropertyOptionsEvent $event)
	{
		$result = array();
		$model  = $event->getModel();

		$metaModel  = self::getMetaModel($model);
		$typeFilter = $GLOBALS['METAMODELS']['filters'][$model->getProperty('type')]['attr_filter'];

		foreach ($metaModel->getAttributes() as $attribute) {
			$typeName = $attribute->get('type');

			if ($typeFilter && (!in_array($typeName, $typeFilter))) {
				continue;
			}

			$strSelectVal          = $metaModel->getTableName() .'_' . $attribute->getColName();
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
	public static function decodeValue(DecodePropertyValueForWidgetEvent $event)
	{
		$model      = $event->getModel();
		$metaModel  = self::getMetaModel($model);
		$values     = $event->getValue();
		$attributes = array();

		if (!($metaModel && $values)) {
			return;
		}

		foreach($values as $value)
		{
			$attribute = $metaModel->getAttributeById($value);
			if($attribute)
			{
				$attributes[] = $metaModel->getTableName() .'_' . $attribute->getColName();
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
	public static function encodeValue(EncodePropertyValueFromWidgetEvent $event)
	{
		$model      = $event->getModel();
		$metaModel  = self::getMetaModel($model);
		$values     = $event->getValue();
		$attributes = array();

		if (!($metaModel && $values)) {
			return;
		}
		foreach($values as $value)
		{
			$value = str_replace($metaModel->getTableName() . '_', '', $value);
			$attribute = $metaModel->getAttribute($value);
			if($attribute)
			{
				$attributes[] = $attribute->get('id');
			}
		}

		if ($attributes) {
			$event->setValue($attributes);
		}
	}
}