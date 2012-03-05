<?php
/**
 * This file declare the PluginChCmsApiFormatterPropertyFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-03
 */

/**
 * formatter to handle properties as a collection
 */
class PluginChCmsApiFormatterPropertyFormatter extends chCmsApiPropertyFormatter
{
  /**
   * constructor
   *
   * @return void
   **/
  public function __construct($field_name, $formatter, $options = array())
  {
    parent::__construct($field_name, array_merge($options, array('formatter' => $formatter)));
  }

  /**
   * format
   *
   * @return void
   */
  public function format($object, $fields = array())
  {
    $formatter = $this->getFormatter();

    return $formatter->format($this->extractProperty($object, $fields));
  }

  /**
   * return the subcollection object formatter
   *
   * @return chCmsApiObjectFormatter
   **/
  public function getFormatter()
  {
    return $this->getOption('formatter');
  }

  /**
   * set an option for both formatter and subformatter
   *
   * @param string $name   the option name
   * @param mixed  $value  the value
   * @return chCmsApiCollectionFormatter
   **/
  public function setOption($name, $value)
  {
    $this->getFormatter()->setOption($name, $value);
    return parent::setOption($name, $value);
  }
} // END OF PluginChCmsApiFormatterPropertyFormatter