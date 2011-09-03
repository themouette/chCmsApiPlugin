<?php
/**
 * This file declare the chCmsApiPropertyFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-03
 */

/**
 * formatter to extract a property from an object
 */
class chCmsApiPropertyFormatter extends BasechCmsApiFormatter
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
   * extract field with $fieldName
   *
   * @return mixed
   */
  public function format($object)
  {
    $fieldName  = $this->getOption('field_name');
    $methodName = sprintf('get%s', ucfirst(sfInflector::camelize($fieldName)));

    return $object->$methodName();
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
} // END OF chCmsApiPropertyFormatter
