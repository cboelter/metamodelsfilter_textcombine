<?php

namespace MetaModels\DcGeneral\Events\Table\FilterSetting\TextCombine;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ModelToLabelEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use MetaModels\DcGeneral\Events\Table\FilterSetting\FilterSettingTypeRenderer;
use MetaModels\IMetaModelsServiceContainer;

/**
 * Handles rendering of model from tl_metamodel_filtersetting.
 */
class FilterSettingTypeRendererTextCombine extends FilterSettingTypeRenderer
{
    /**
     * The MetaModel service container.
     *
     * @var IMetaModelsServiceContainer
     */
    private $serviceContainer;

    /**
     * Create a new instance.
     *
     * @param IMetaModelsServiceContainer $serviceContainer The MetaModel service container.
     */
    public function __construct(IMetaModelsServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;

        $this->getServiceContainer()->getEventDispatcher()->addListener(
            ModelToLabelEvent::NAME,
            array($this, 'modelToLabelTextCombine')
        );
    }

    /**
     * Retrieve the service container.
     *
     * @return IMetaModelsServiceContainer
     */
    protected function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * Retrieve the types this renderer is valid for.
     *
     * @return array
     */
    protected function getTypes()
    {
        return array('textcombine');
    }

    /**
     * Retrieve the parameters for the label.
     *
     * @param EnvironmentInterface $environment The translator in use.
     *
     * @param ModelInterface       $model       The model.
     *
     * @return array
     */
    protected function getLabelParametersTextCombine(EnvironmentInterface $environment, ModelInterface $model)
    {
        if ($model->getProperty('type') == 'textcombine') {
            return $this->getLabelParametersWithMultipleAttributeAndUrlParam($environment, $model);
        }
    }

    /**
     * Retrieve the parameters for the label with attribute name and url parameter.
     *
     * @param EnvironmentInterface $environment The translator in use.
     *
     * @param ModelInterface       $model       The model.
     *
     * @return array
     */
    protected function getLabelParametersWithMultipleAttributeAndUrlParam(
        EnvironmentInterface $environment,
        ModelInterface $model
    ) {
        $translator = $environment->getTranslator();
        $metaModel  = $this->getMetaModel($model);
        $attributes  = (array)$model->getProperty('textcombine_attributes');
        $attributeNames = array();

        if(!is_array($attributes)) {
            return;
        }

        foreach ($attributes as $attribute) {
            $attribute = $metaModel->getAttributeById($attribute);

            if(!$attribute) {
                continue;
            }

            $attributeNames[] = $attribute->getName();
        }

        if (count($attributeNames) > 0) {
            $attributeName = implode(', ', $attributeNames);
        } else {
            $attributeName = $model->getProperty('textcombine_attributes');
        }

        return array(
            $this->getLabelImage($model),
            $this->getLabelText($translator, $model),
            $this->getLabelComment($model, $translator),
            $attributeName,
            ($model->getProperty('urlparam') ? $model->getProperty('urlparam') : $attributeName)
        );
    }

    /**
     * Render a filter setting into html.
     *
     * @param ModelToLabelEvent $event The event.
     *
     * @return void
     */
    public function modelToLabelTextCombine(ModelToLabelEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName()
                !== 'tl_metamodel_filtersetting')
            || !in_array($event->getModel()->getProperty('type'), $this->getTypes())
        ) {
            return;
        }

        $environment = $event->getEnvironment();
        $model       = $event->getModel();

        $event
            ->setLabel($this->getLabelPattern($environment, $model))
            ->setArgs($this->getLabelParametersTextCombine($environment, $model));
    }
}
