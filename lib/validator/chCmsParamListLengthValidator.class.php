<?php
/**
 * This file declare the chCmsParamListLengthValidator class.
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
class chCmsParamListLengthValidator extends chCmsIntegerDefaultParamValidator
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
    $this->setOption('min', 1);
  }
} // END OF chCmsParamListLengthValidator
