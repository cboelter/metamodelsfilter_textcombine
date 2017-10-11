<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage FilterTextCombine
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  2017 Christopher Bölter
 * @license    https://github.com/cboelter/metamodelsfilter_textcombine/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Filter\Setting;

use MetaModels\Filter\Filter;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\Condition\ConditionAnd;
use MetaModels\Filter\Rules\Condition\ConditionOr;
use MetaModels\Filter\Rules\SearchAttribute;
use MetaModels\Filter\Rules\StaticIdList;
use MetaModels\FrontendIntegration\FrontendFilterOptions;
use MetaModels\Filter\Setting\Simple;

/**
 * Filter "text combine" for FE-filtering, based on filters by the MetaModels team.
 *
 * @package    MetaModels
 * @subpackage FilterTextCombine
 */
class TextCombine extends Simple
{

    /**
     * Return the setted urlparam or generate one.
     *
     * @return mixed|string
     */
    protected function getParamName()
    {
        if ($this->get('urlparam')) {
            return $this->get('urlparam');
        }

        return 'textsearch_' . $this->get('id');
    }

    /**
     * Prepare the filter rule.
     *
     * @param IFilter $objFilter    The current filter object.
     * @param array   $arrFilterUrl The current filter url.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @return void
     */
    public function prepareRules(IFilter $objFilter, $arrFilterUrl)
    {
        $objMetaModel  = $this->getMetaModel();
        $strParamName  = $this->getParamName();
        $strParamValue = $arrFilterUrl[$strParamName];
        $strTextsearch = $this->get('textsearch');
        $arrAttributes = deserialize($this->get('textcombine_attributes'));

        // react on wildcard, overriding the search type
        if (strpos($strParamValue, '*') !== false) {
            $strTextsearch = 'exact';
        }

        // type of search
        switch ($strTextsearch) {
            case 'beginswith':
                $strWhat = $strParamValue . '%';
                break;
            case 'endswith':
                $strWhat = '%' . $strParamValue;
                break;
            case 'exact':
                $strWhat = $strParamValue;
                break;
            default:
                $strWhat = '%' . $strParamValue . '%';
                break;
        }

        if ($strParamName && $strParamValue) {
            if ($this->get('textcombine_operator') == 'and') {
                $objParentRule = new ConditionAnd();
            }

            if ($this->get('textcombine_operator') == 'or') {
                $objParentRule = new ConditionOr();
            }

            foreach ($arrAttributes as $intAttribute) {
                $objAttribute = $objMetaModel->getAttributeById($intAttribute);

                if ($objAttribute) {
                    $objSubFilter = new Filter($objMetaModel);
                    $objSubFilter->addFilterRule(new SearchAttribute($objAttribute, $strWhat));
                    $objParentRule->addChild($objSubFilter);
                }
            }

            $objFilter->addFilterRule($objParentRule);

            return;
        }

        $objFilter->addFilterRule(new StaticIdList(null));
    }

    /**
     * Return param name.
     *
     * @return array
     */
    public function getParameters()
    {
        return ($strParamName = $this->getParamName()) ? array($strParamName) : array();
    }

    /**
     * Get the filter param name.
     *
     * @return array
     */
    public function getParameterFilterNames()
    {
        return array(
            $this->getParamName() => ($this->get('label') ? $this->get('label') : 'Textcombine')
        );
    }

    /**
     * Generate the filter widget for the frontend.
     *
     * @param array                 $arrIds                   The given ids.
     * @param array                 $arrFilterUrl             The current filter url.
     * @param array                 $arrJumpTo                The current jumpto page.
     * @param FrontendFilterOptions $objFrontendFilterOptions The given frontend filter options.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function getParameterFilterWidgets(
        $arrIds,
        $arrFilterUrl,
        $arrJumpTo,
        FrontendFilterOptions $objFrontendFilterOptions
    ) {
        // if defined as static, return nothing as not to be manipulated via editors.
        if (!$this->enableFEFilterWidget()) {
            return array();
        }

        $this->addFilterParam($this->getParamName());

        $arrWidget = array(
            'label'     => array(
                ($this->get('label') ? $this->get('label') : 'textcombine'),
                'GET: ' . $this->getParamName()
            ),
            'inputType' => 'text',
            'eval'      => array(
                'urlparam'    => $this->getParamName(),
                'template'    => $this->get('template'),
                'placeholder' => $this->get('placeholder'),
            )
        );

        return array(
            $this->getParamName() => $this->prepareFrontendFilterWidget(
                $arrWidget,
                $arrFilterUrl,
                $arrJumpTo,
                $objFrontendFilterOptions
            )
        );
    }

    /**
     * Overrides the parent implementation to always return true, as this setting is always optional.
     *
     * @return bool true if all matches shall be returned, false otherwise.
     */
    public function allowEmpty()
    {
        return true;
    }

    /**
     * Overrides the parent implementation to always return true, as this setting is always available for FE filtering.
     *
     * @return bool true as this setting is always available.
     */
    public function enableFEFilterWidget()
    {
        return true;
    }

    /**
     * Add Param to global filter params array.
     *
     * @param string $strParam Name of filter param.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    private function addFilterParam($strParam)
    {
        $GLOBALS['MM_FILTER_PARAMS'][] = $strParam;
    }
}
