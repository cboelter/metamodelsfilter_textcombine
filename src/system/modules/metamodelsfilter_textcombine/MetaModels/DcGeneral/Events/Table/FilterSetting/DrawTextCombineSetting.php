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

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use MetaModels\Filter\Setting\Factory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use MetaModels\DcGeneral\Events\Table\FilterSetting\DrawSetting;

/**
 * Draw a filter setting in the backend.
 */
class DrawTextCombineSetting extends DrawSetting
{

	/**
	 * Render the special setting for textcombine filter.
	 *
	 * @param ModelToLabelEvent $event The event.
	 *
	 * @return void
	 */
	public static function modelToLabelTextCombine(ModelToLabelEvent $event)
	{
		$environment 	= $event->getEnvironment();
		$model       	= $event->getModel();
		$metamodel   	= Factory::byId($model->getProperty('fid'))->getMetaModel();
		$attributes 	= (array)$model->getProperty('textcombine_attributes');
		$attributenames = array();

		foreach ($attributes as $attribute) {
			$modelattribute = $metamodel->getAttributeById($attribute);

			if (!$modelattribute) {
				continue;
			}

			$attributenames[] = $modelattribute->getName();
		}

		$event
			->setLabel(self::getLabelPattern($environment, $model))
			->setArgs(array(
					self::getLabelImage($model, $event->getDispatcher()),
					self::getLabelText($environment, $model),
					self::getLabelComment($model, $environment),
					implode(', ', $attributenames)
				));
	}
}