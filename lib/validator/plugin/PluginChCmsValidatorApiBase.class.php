<?php
/**
 * This file declare the PluginChCmsValidatorApiBase class.
 *
 * @package chCmsApiPlugin
 * @subpackage parma-validator
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * param validator
 */
abstract class PluginChCmsValidatorApiBase extends sfValidatorBase
{
  /**
   * setup
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   * @return void
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('comment', 'you can provide a comment option to validator.');
  }
  /**
   * throw an API error without validating other values.
   * in such case parameters are append.
   *
   * @param mixed   $code     the error code
   * @param string  $message  the error message
   * @throw chCmsError400Exception
   */
  protected function throwApiError($code = null, $message = null)
  {
    throw new chCmsError400Exception($code, $message);
  }
} // END OF PluginChCmsValidatorApiBase
