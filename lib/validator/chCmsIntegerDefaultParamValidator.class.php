<?php
/**
 * This file declare the chCmsNumberDefaultParamValidator class.
 *
 * @package chCmsContactsPlugin
 * @subpackage validator
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-09
 */

/**
 * validator for api list length parameter
 */
class chCmsIntegerDefaultParamValidator extends sfValidatorInteger
{
  /**
   * available options
   * * default: required
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   * @return void
   **/
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
   **/
  protected function doClean($value)
  {
    $default = $this->getOption('default');
    if (is_null($value))
    {
      $value = $default;
    }

    $value = parent::doClean($value);

    return min($value, $default);
  }
} // END OF chCmsNumberDefaultParamValidator
