<?php
/**
 * This file declare the PluginChCmsApiFormatterTestObject class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  test
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-03
 */

/**
 * test object for formatter tests
 */
class PluginChCmsApiFormatterTestObject
{
  protected $values = array();

  /**
   * return array values
   *
   * @return array
   **/
  public function toArray()
  {
    return $this->values;
  }

  /**
   * constructor
   *
   * @return void
   **/
  public function __construct($values = array())
  {
    $this->values = $values;
  }

  /**
   * magic getter
   **/
  public function __get($name)
  {
    return isset($this->values[$name]) ? $this->values[$name] : null;
  }

  /**
   * magic setter
   **/
  public function __set($name, $value)
  {
    $this->values[$name] = $value;
  }

  /**
   * getter for anay value
   *
   * @return mixed
   **/
  public function __call($method, $args)
  {
    if (0 === strpos($method, 'get'))
    {
      if (!isset($this->values[sfInflector::underscore(substr($method, 3))]))
      {
        return null;
      }
      return $this->values[sfInflector::underscore(substr($method, 3))];
    }
    if (0 === strpos($method, 'set'))
    {
      $this->values[sfInflector::underscore(substr($method, 3))] = $args[0];
      return $this;
    }
    throw new LogicException(sprintf('unknown method %s', $method));
  }
} // END OF PluginChCmsApiFormatterTestObject