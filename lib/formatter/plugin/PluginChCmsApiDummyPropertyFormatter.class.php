<?php
/**
 * This file declare the PluginChCmsApiDummyPropertyFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-04
 */

/**
 * force output value to given one, whatever the input is
 */
class PluginChCmsApiDummyPropertyFormatter extends chCmsApiPropertyFormatter
{
  /**
   * constructor
   *
   * @return void
   */
  public function __construct($value = null, $options = array())
  {
    parent::__construct(null, array_merge($options, array(
      'value'  => $value
    )));
  }

  /**
   * format
   *
   * @return mixed
   */
  public function format($object)
  {
    return $this->getOption('value', null);
  }
} // END OF PluginChCmsApiDummyPropertyFormatter