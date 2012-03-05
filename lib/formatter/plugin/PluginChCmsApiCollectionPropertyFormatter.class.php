<?php
/**
 * This file declare the PluginChCmsApiCollectionPropertyFormatter class.
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
class PluginChCmsApiCollectionPropertyFormatter extends chCmsApiPropertyFormatter
{
  /**
   * constructor
   *
   * @return void
   **/
  public function __construct($field_name, $formatter, $options = array())
  {
    if (!($formatter instanceof chCmsApiCollectionFormatter))
    {
      $formatter = new chCmsApiCollectionFormatter($formatter, $options);
    }

    parent::__construct($field_name, array_merge($options, array('formatter' => $formatter)));
  }

  /**
   * format
   *
   * @return void
   */
  public function format($object, $fields = array())
  {
    $formatter = $this->getCollectionFormatter();

    return $formatter->format(parent::format($object, $fields));
  }

  /**
   * return the subcollection object formatter
   *
   * @return chCmsApiObjectFormatter
   **/
  public function getFormatter()
  {
    return $this->getCollectionFormatter()->getFormatter();
  }

  /**
   * return the collection formatter
   *
   * @return chCmsApiCollectionFormatter
   **/
  public function getCollectionFormatter()
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
    $this->getCollectionFormatter()->setOption($name, $value);
    return parent::setOption($name, $value);
  }
} // END OF PluginChCmsApiCollectionPropertyFormatter