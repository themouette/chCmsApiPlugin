<?php
/**
 * This file declare the PluginChCmsApiObjectFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-07-08
 */

/**
 * the base formatter object.
 * To declare a formatter, just extend this class and setDefaultFormatFields
 * in the initialize method.
 */
class PluginChCmsApiObjectFormatter extends chCmsApiArrayFormatter
{
  /**
   * extract property from object
   *
   * @return mixed
   */
  public function extractProperty($object, $field_name)
  {
    $methodName = sprintf('get%s', ucfirst(sfInflector::camelize($field_name)));

    return $object->$methodName();
  }

  /**
   * format object according to formatter configuration
   *
   * @return stdObject
   **/
  public function format($object, $fields = array())
  {
    // in case a simple formatter is given to handle collection
    if (is_array($object) || ($object instanceof PropelCollection))
    {
      return $this->formatCollection($object, $fields);
    }

    return parent::format($object, $fields);
  }

  /**
   * format object
   *
   * @param BaseObject|array        $object object to format
   * @param array|null              $fields     fields to use
   * @return array
   **/
  public function formatObject($object, $fields = array())
  {
    return $this->format($object, $fields);
  }

  /**
   * format a pager.
   *
   * @param PropelPager  $pager    collection to format
   * @param array|null   $fields   fields to use
   * @return array
   */
  public function formatPager($pager, $fields = array(), $options = array())
  {
    $objectFormatter = clone($this);
    $objectFormatter->mergeFormatFields($fields);
    $formatter = new chCmsApiPagerFormatter($objectFormatter, $options);
    return $formatter->format($pager);
  }
} // END OF PluginChCmsApiObjectFormatter