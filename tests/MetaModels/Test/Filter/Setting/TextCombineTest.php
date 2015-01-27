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
 * @subpackage Tests
 * @author     Christopher Boelter <christopher@boelter.eu>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\Test\Filter\Setting;

use MetaModels\Filter\Setting\Simple;

/**
 * Test textcombine filter settings.
 */
class TextCombineTest extends TestCase
{
    /**
     * Mock a TextCombine filter setting.
     *
     * @param array  $properties The initialization data.
     *
     * @param string $tableName  The table name of the MetaModel to mock.
     *
     * @return TextCombine
     */
    protected function mockSimpleFilterSetting($properties = array(), $tableName = 'mm_unittest')
    {
        $setting = $this->getMock(
            'MetaModels\Filter\Setting\TextCombine',
            array(),
            array($this->mockFilterSetting($tableName), $properties)
        );

        return $setting;
    }

    /**
     * Add a parameter to the url.
     *
     * @param Simple $instance The instance.
     *
     * @param string $url      The url built so far.
     *
     * @param string $name     The parameter name.
     *
     * @param string $value    The parameter value.
     *
     * @return string.
     */
    protected function addUrlParameter($instance, $url, $name, $value)
    {
        $reflection = new \ReflectionMethod($instance, 'addUrlParameter');
        $reflection->setAccessible(true);
        return $reflection->invoke($instance, $url, $name, $value);
    }

    /**
     * Build the filter url for the filter configuration.
     *
     * @param Simple $instance  The instance.
     *
     * @param array  $params    The filter url parameter array.
     *
     * @param string $paramName The filter url parameter name.
     *
     * @return string
     */
    protected function buildFilterUrl($instance, $params, $paramName)
    {
        $reflection = new \ReflectionMethod($instance, 'buildFilterUrl');
        $reflection->setAccessible(true);
        return $reflection->invoke($instance, $params, $paramName);
    }

    /**
     * Test adding of filter url parameters.
     *
     * @return void
     */
    public function testAddUrlParameter()
    {
        $setting = $this->mockSimpleFilterSetting();

        $this->assertEquals(
            '/a/A/b/B/textsearch/foo',
            $this->addUrlParameter($setting, '/a/A/b/B', 'textsearch', 'foo'),
            'textsearch'
        );
    }

    /**
     * Test building of filter urls.
     *
     * @return void
     */
    public function testBuildFilterUrl()
    {
        $setting = $this->mockSimpleFilterSetting();

        $this->assertEquals(
            '/a/A/b/B%s',
            $this->buildFilterUrl($setting, array('a' => 'A', 'b' => 'B', 'textsearch' => 'foo'), 'textsearch'),
            'textsearch'
        );
    }
}
