<?php
/**
 * This file declare the ##validatorClass## class.
 *
 * @package ##package##
 * @subpackage parma-validator
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * param validator
 */
class ##validatorClass## extends ##BaseValidator##
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
    parent::configure($options, $messages);
    $this->setOption('required', false);
    $this->addOption('default', 20);
  }

  protected function getEmptyValue()
  {
    return $this->getOption('default');
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    $value = parent::doClean($value);

    return $value;
  }
} // END OF ##validatorClass##
