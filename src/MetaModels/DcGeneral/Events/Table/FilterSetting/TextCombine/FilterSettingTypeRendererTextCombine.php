<?php

namespace MetaModels\DcGeneral\Events\Table\FilterSetting\TextCombine;

use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use MetaModels\DcGeneral\Events\Table\FilterSetting\FilterSettingTypeRenderer;

/**
 * Handles rendering of model from tl_metamodel_filtersetting.
 */
class FilterSettingTypeRendererTextCombine extends FilterSettingTypeRenderer
{
    /**
     * Retrieve the types this renderer is valid for.
     *
     * @return array
     */
    protected function getTypes()
    {
        return array('idlist', 'simplelookup', 'customsql', 'conditionand', 'conditionor');
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
    protected function getLabelParameters(EnvironmentInterface $environment, ModelInterface $model)
    {
        if ($model->getProperty('type') == 'textcombine') {
            return $this->getLabelParametersWithMultipleAttributeAndUrlParam($environment, $model);
        }

        return $this->getLabelParametersNormal($environment, $model);
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
}
