<?php
/**
 * This file declare the PluginChCmsApiPropertyFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-03
 */

/**
 * formatter to extract a property from an object
 */
class PluginChCmsApiPropertyFormatter extends BasechCmsApiFormatter
{
  /**
   * constructor for chCmsApiPropertyFormatter
   *
   * @return void
   */
  public function __construct($field_name, $options = array())
  {
    parent::__construct(array_merge($options, array('field_name' => $field_name)));
  }

  /**
   * extract property from object
   *
   * @return mixed
   */
  protected function extractProperty($object)
  {
    $field_name = $this->getOption('field_name');
    if ($this->getOption('extract_method', false))
    {
      return call_user_func($this->getOption('extract_method'), $object, $field_name);
    }

    $methodName = sprintf('get%s', ucfirst(sfInflector::camelize($field_name)));
    return $object->$methodName();
  }

  /**
   * extract field with $fieldName
   *
   * @return mixed
   */
  public function format($object)
  {
    return $this->extractProperty($object);
  }

  /**
   * return the name of field to format
   *
   * @return string
   */
  public function getFieldName()
  {
    return $this->getOption('field_name');
  }
} // END OF PluginChCmsApiPropertyFormatter