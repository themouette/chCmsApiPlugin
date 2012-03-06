<?php
/**
 * This file declare the PluginChCmsParamDateIntervalValidator class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  param-validator
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-04
 */

/**
 * param validator
 */
class PluginChCmsParamDateIntervalValidator extends chCmsIntegerDefaultParamValidator
{
  /**
   * configure the widget.
   * add following options :
   *  - default (null) the default value if no given data
   *
   * @note This validator should be used as post-validator
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
    $this->addOption('start_field', 'start_date');
    $this->addOption('end_field', 'end_date');
    $this->addOption('interval_field', 'interval');

    parent::configure($options, $messages);

    $this->setMessage('invalid', 'Invalid interval "%interval%".');
    $this->addMessage('inconsistent', 'Incorrect interval "%interval%".');
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
    // the validator is not used as post-validator
    if (!is_array($value))
    {
      return $this->trySplitInterval($value);
    }

    $interval_field = $this->getOption('interval_field');

    // the validator is used as a post-validator
    // and the "inverval" field is given
    if (!empty($value[$interval_field]))
    {
      $value[$interval_field] = $this->trySplitInterval($value[$interval_field]);
      return $value;
    }
    else if (!empty($value[$this->getOption('start_field')]) && !empty($value[$this->getOption('end_field')]))
    {
      $value[$interval_field] = $this->cleanInterval(
        $value[$this->getOption('start_field')],
        $value[$this->getOption('end_field')]
      );

      return $value;
    }

    if ($this->getOption('required', true))
    {
      throw new sfValidatorError($this, 'required');
    }

    return $value;
  }

  protected function trySplitInterval($value)
  {
    $dates = explode($this->getOption('separator'), $value);

    // we should have a start and an end date.
    if (count($dates) !== 2)
    {
      throw new sfValidatorError($this, 'invalid', array('interval' => $value));
    }

    return $this->cleanInterval($dates[0], $dates[1]);
  }

  protected function cleanInterval($origin_start, $origin_end)
  {
    $start = $this->validateDate($origin_start);
    $end = $this->validateDate($origin_end);

    $interval = implode($this->getOption('separator'), array($origin_start, $origin_end));

    // now that we have our dates, let's check if they are consistent
    if ($start >= $end)
    {
      throw new sfValidatorError($this, 'inconsistent', array('interval' => $interval));
    }

    if ($min_start = $this->getOption('min_start'))
    {
      if ($start->getTimestamp() < $min_start)
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $interval));
      }
    }

    // min interval check
    if ($min_interval = $this->getOption('min_interval'))
    {
      if (($end->getTimestamp() - $start->getTimestamp()) < strtotime($min_interval) - time())
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $interval));
      }
    }

    // max interval check
    if ($max_interval = $this->getOption('max_interval'))
    {
      if (($end->getTimestamp() - $start->getTimestamp()) > strtotime($max_interval) - time())
      {
        throw new sfValidatorError($this, 'inconsistent', array('interval' => $interval));
      }
    }

    return array($start, $end);
  }
} // END OF PluginChCmsParamDateIntervalValidator
