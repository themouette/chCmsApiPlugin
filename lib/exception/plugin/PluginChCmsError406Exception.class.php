<?php
/**
 * This file declare the PluginChCmsError406Exception class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  exception
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-07-08
 */

/**
 * 406 error exception
 */
class PluginChCmsError406Exception extends chCmsApiErrorException
{
  /**
   * exception constructor
   *
   * @param mixed   $code     the error code
   * @param string  $message  the error message
   * @param array   $parameters   extra param to return
   **/
  public function __construct($code = null, $message = null, $parameters = null)
  {
    parent::__construct($code, $message, 406, $parameters);
  }
} // END OF PluginChCmsError406Exception
