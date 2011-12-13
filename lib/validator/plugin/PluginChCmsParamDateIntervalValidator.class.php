<?php
/**
 * This file declare the PluginChCmsParamDateIntervalValidator class.
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
class PluginChCmsParamDateIntervalValidator extends chCmsValidatorApiBase
{
  /**
   * configure the widget.
   * add following options :
   *  - default (null) the default value if no given data
   *
   * @param array $options  the validator options
   * @param array $messages error messages
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('default', null);
    $this->addOption('separator', '|');
    $this->addOption('min_start', strtotime('today'));
    $this->addOption('min_interval', null);
    $this->addOption('max_interval', '1 week');
    $this->addOption('date_validator', null);

    $this->setMessage('invalid', 'Invalid interval "%interval%".');
    $this->addMessage('inconsistent', 'Incorrect interval "%interval%".');

    parent::configure($options, $messages);
  }

  protected function getDateValidator()
  {
    $validator = $this->getOption('date_validator');
    if ($validator)
    {
      return $validator;
    }

    return new chCmsParamDateValidator(array('required' => true));
  }

  protected function validateDate($date)
  {
    return $this->getDateValidator()->clean($date);
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    $dates = explode($this->getOption('separator'), $value);

    // we should have a start and an end date.
    if (count($dates) !== 2)
    {
      throw new sfValidatorError($this, 'invalid', array('interval' => $value));
    }

    $start = $this->validateDate($dates[0]);
    $end = $this->validateDate($dates[1]);

    // now that we have our dates, let's check if they are consistent
    if ($start >= $end)
    {
      throw new sfValidatorError($this, 'inconsistent', array('interval' => $value));
    }

    if ($min_start = $this->getOption('min_start'))
    {
      if ($start->getTimestamp() < $min_start)
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $value));
      }
    }

    // min interval check
    if ($min_interval = $this->getOption('min_interval'))
    {
      if (($end->getTimestamp() - $start->getTimestamp()) < strtotime($min_interval) - time())
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $value));
      }
    }

    // max interval check
    if ($max_interval = $this->getOption('max_interval'))
    {
      if (($end->getTimestamp() - $start->getTimestamp()) > strtotime($max_interval) - time())
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $value));
      }
    }

    return array($start, $end);
  }
} // END OF PluginChCmsParamDateIntervalValidator
