<?php
/**
 * This file declare the PluginChCmsParamDateValidator class.
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
class PluginChCmsParamDateValidator extends chCmsValidatorApiBase
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
    $this->addOption('default', null);
    $this->addOption('format', DateTime::ISO8601);

    $this->addMessage('error', 'Following error occured "%error%".');
    $this->setMessage('invalid', 'Invalid date "%date%".');
    $this->setMessage('required', 'Please provide a date.');

    parent::configure($options, $messages);
  }

  protected function getEmptyValue()
  {
    $default = $this->getOption('default');

    if (is_null($default))
    {
      return null;
    }

    try
    {
      $date = new DateTime($default);
    }
    catch (Exception $e)
    {
      $date = DateTime::createFromFormat($this->getOption('format', DateTime::ISO8601), $default);
    }

    return $date;
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    try
    {
      $date = DateTime::createFromFormat($this->getOption('format', DateTime::ISO8601), $value);
    }
    catch (Exception $e)
    {
      throw new sfValidatorError($this, 'error', array('error' => $e->getMessage(), 'date' => $value));
    }

    if (!$date)
    {
      throw new sfValidatorError($this, 'invalid', array('date' => $value));
    }

    return $date;
  }
} // END OF PluginChCmsParamDateValidator
