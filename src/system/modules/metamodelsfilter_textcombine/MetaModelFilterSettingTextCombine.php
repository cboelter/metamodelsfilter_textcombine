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
 * Filter "text combine" for FE-filtering, based on filters by the MetaModels team.
 *
 * @package	   MetaModels
 * @subpackage FilterTextCombine
 * @author     Christopher Bölter <c.boelter@cogizz.de>
 */
class MetaModelFilterSettingTextCombine extends MetaModelFilterSetting
{
	/**
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function prepareRules(IMetaModelFilter $objFilter, $arrFilterUrl)
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

		$arrQuery = array();

		if ($strParamName && $strParamValue)
		{
			foreach($arrAttributes as $intAttribute) {
				$objAttribute = $objMetaModel->getAttributeById($intAttribute);

				if($objAttribute) {
					$arrQuery[] = sprintf('%s LIKE ?', $objAttribute->getColName());
					$arrParams[] = str_replace(array('*', '?'), array('%', '_'), $strWhat);
				}
			}

			if(count($arrQuery) && count($arrParams)) {
				$strQuery = sprintf(
					'SELECT id FROM %s WHERE %s',
					$this->getMetaModel()->getTableName(),
					'(' . implode(' OR ', $arrQuery) . ')'
				);

				$objFilter->addFilterRule(new MetaModelFilterRuleSimpleQuery($strQuery, $arrParams));
				return;
			}
		}

		$objFilter->addFilterRule(new MetaModelFilterRuleStaticIdList(NULL));
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		return ($strParamName = $this->getParamName()) ? array($strParamName) : array();
	}


	/**
	 * {@inheritdoc}
	 */
	public function getParameterFilterNames()
	{
		return array(
			$this->getParamName() => 'Textcombine'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameterFilterWidgets($arrIds, $arrFilterUrl, $arrJumpTo, $blnAutoSubmit, $blnHideClearFilter)
	{
		// if defined as static, return nothing as not to be manipulated via editors.
		if (!$this->enableFEFilterWidget())
		{
			return array();
		}

		$arrWidget = array(
			'label'     => array(
				// TODO: make this multilingual.
				($this->get('label') ? $this->get('label') : 'textcombine'),
				'GET: ' . $this->getParamName()
			),
			'inputType'    => 'text',
			'eval' => array(
				'urlparam'           => $this->getParamName(),
				'template'           => $this->get('template'),
			)
		);

		return array
		(
			$this->getParamName() => $this->prepareFrontendFilterWidget($arrWidget, $arrFilterUrl, $arrJumpTo, $blnAutoSubmit)
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