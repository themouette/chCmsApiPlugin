<?php
/**
 * This file declare the PluginChCmsPageParamValidator class.
 *
 * @package     chCmsContactsPlugin
 * @subpackage  validator
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-07-09
 */

/**
 * validator for api list length parameter
 */
class PluginChCmsPageParamValidator extends sfValidatorInteger
{
  /**
   * available options
   * * default: default page
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   * @return void
   **/
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('required', false);
    $this->setOption('min', 1);
    $this->addOption('default', 1);
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
    try
    {
      return parent::doClean($value);
    }
    catch (sfValidatorError $e)
    {
      if ('max' === $e->getCode())
      {
        return $this->getOption('max');
      }
      // rethrow
      throw $e;
    }
  }
} // END OF PluginChCmsPageParamValidator