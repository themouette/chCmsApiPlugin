<?php
/**
 * This file declare the PluginChCmsApiValidatorParamNotYetImplemented class.
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
class PluginChCmsApiValidatorParamNotYetImplemented extends chCmsValidatorApiBase
{
  /**
   * configure the widget.
   * add following options :
   *  - default (20) the default value if no given data
   *
   * @param array $options  the validator options
   * @param array $messages error messages
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->setOption('required', false);
    $this->addMessage('not_implmented', 'this parameter is not implmented yet.');
    parent::configure($options, $messages);
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    throw new sfValidatorError($this, 'not_implmented');
  }
} // END OF PluginChCmsApiValidatorParamNotYetImplemented
