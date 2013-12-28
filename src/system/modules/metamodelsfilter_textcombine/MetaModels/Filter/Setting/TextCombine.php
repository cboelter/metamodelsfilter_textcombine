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


namespace MetaModels\Filter\Setting;

use MetaModels\Filter\Filter;
use MetaModels\Filter\IFilter;
use MetaModels\Filter\Rules\Condition\ConditionAnd;
use MetaModels\Filter\Rules\Condition\ConditionOr;
use MetaModels\Filter\Rules\SearchAttribute;
use MetaModels\Filter\Rules\StaticIdList;
use MetaModels\FrontendIntegration\FrontendFilterOptions;

/**
 * Filter "text combine" for FE-filtering, based on filters by the MetaModels team.
 *
 * @package	   MetaModels
 * @subpackage FilterTextCombine
 * @author     Christopher Bölter <c.boelter@cogizz.de>
 */
class TextCombine extends Simple
{

	/**
	 * return the setted urlparam or generate one
	 * @return mixed|string
	 */
	protected function getParamName()
	{

		if ($this->get('urlparam'))
		{
			return $this->get('urlparam');
		}

		return 'textsearch_' . $this->get('id');

	}

	/**
	 * prepare the filter rule
	 * @param Filter $objFilter
	 * @param $arrFilterUrl
	 */
	public function prepareRules(IFilter $objFilter, $arrFilterUrl)
	{
		$objMetaModel = $this->getMetaModel();
		$strParamName = $this->getParamName();
		$strParamValue = $arrFilterUrl[$strParamName];
		$strTextsearch = $this->get('textsearch');
		$arrAttributes = deserialize($this->get('textcombine_attributes'));

		// react on wildcard, overriding the search type
		if(strpos($strParamValue,'*')!==false)
		{
			$strTextsearch = 'exact';
		}

		// type of search
		switch($strTextsearch)
		{
			case 'beginswith':
				$strWhat = $strParamValue.'%';
				break;
			case 'endswith':
				$strWhat = '%'.$strParamValue;
				break;
			case 'exact':
				$strWhat = $strParamValue;
				break;
			default:
				$strWhat = '%'.$strParamValue.'%';
				break;
		}

		if ($strParamName && $strParamValue)
		{
			if ($this->get('textcombine_operator') == 'and') {
				$objParentRule = new ConditionAnd();
			}

			if($this->get('textcombine_operator') == 'or') {
				$objParentRule = new ConditionOr();
			}

			foreach($arrAttributes as $intAttribute) {
				$objAttribute = $objMetaModel->getAttributeById($intAttribute);

				if($objAttribute) {
					$objSubFilter = new Filter($objMetaModel);
					$objSubFilter->addFilterRule(new SearchAttribute($objAttribute, $strWhat));
					$objParentRule->addChild($objSubFilter);
				}
			}

			$objFilter->addFilterRule($objParentRule);
			return;
		}

		$objFilter->addFilterRule(new StaticIdList(NULL));
	}

	/**
	 * return param name
	 * @return array
	 */
	public function getParameters()
	{
		return ($strParamName = $this->getParamName()) ? array($strParamName) : array();
	}

	/**
	 * get the filter param name
	 * @return array
	 */
	public function getParameterFilterNames()
	{
		return array(
			$this->getParamName() => ($this->get('label') ? $this->get('label') : 'Textcombine')
		);
	}

	/**
	 * generate the filter widget for the frontend
	 *
	 * @param array $arrIds
	 * @param array $arrFilterUrl
	 * @param array $arrJumpTo
	 * @param FrontendFilterOptions $objFrontendFilterOptions
	 * @return array
	 */
	public function getParameterFilterWidgets($arrIds, $arrFilterUrl, $arrJumpTo, FrontendFilterOptions $objFrontendFilterOptions)
	{
		// if defined as static, return nothing as not to be manipulated via editors.
		if (!$this->enableFEFilterWidget())
		{
			return array();
		}

		$arrWidget = array(
			'label' => array(
				// TODO: make this multilingual.
				($this->get('label') ? $this->get('label') : 'textcombine'),
				'GET: ' . $this->getParamName()
			),
			'inputType' => 'text',
			'eval' => array(
				'urlparam' => $this->getParamName(),
				'template' => $this->get('template'),
			)
		);

		return array
		(
			$this->getParamName() => $this->prepareFrontendFilterWidget($arrWidget, $arrFilterUrl, $arrJumpTo, $objFrontendFilterOptions)
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
}