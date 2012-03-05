<?php
/**
 * This file declare the PluginBasechCmsApiFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-03
 */

/**
 * class to handle translation from object property to exported array
 */
abstract class PluginBasechCmsApiFormatter
{
  protected $options;

  /**
   * format $object to format
   *
   * @return mixed
   */
  abstract public function format($object);

  /**
   * constructor
   *
   * @param array $options  the options to force
   * @return void
   **/
  public function __construct($options = array())
  {
    $this->setOptions($options);
  }

  /**
   * set an option
   *
   * @param string $optionName  the option name
   * @param mixed  $value       the option value
   * @return PluginBasechCmsApiFormatter
   */
  public function setOption($optionName, $value)
  {
    $this->options[$optionName] = $value;
    return $this;
  }

  /**
   * retrieve an option
   *
   * @param string $optionName  the option name
   * @param mixed  $value       the option default value
   * @return mixed
   **/
  public function getOption($optionName, $default = null)
  {
    return isset($this->options[$optionName]) ? $this->options[$optionName] : $default;
  }

  /**
   * force all options
   *
   * @param array $options  the options to force
   * @return PluginBasechCmsApiFormatter
   */
  public function setOptions($options)
  {
    $this->options = $options;
    return $this;
  }

  /**
   * retrieve all options
   *
   * @return array
   **/
  public function getOptions()
  {
    return $this->options;
  }
} // END OF PluginBasechCmsApiFormatter