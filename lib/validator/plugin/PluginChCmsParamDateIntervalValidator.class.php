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
    $this->addOption('current_date', time());
    $this->addOption('min_interval', null);
    $this->setMessage('invalid', 'Invalid interval "%interval%".');

    parent::configure($options, $messages);
  }

  public function getCurrentDate()
  {
    $cur_date = $this->getOption('current_date');

    return is_numeric($cur_date) ? $cur_date : strtotime($cur_date);
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    // will iterate through the days, hours, etc.
    foreach (DateInterval::createFromDateString($value) as $val)
    {
      if ($val !== 0)
      {
        if (!($min_interval = $this->getOption('min_interval')))
        {
          return $value;
        }

        $interval_end = strtotime($value, $this->getCurrentDate());
        $min_end = strtotime($min_interval, $this->getCurrentDate());

        // the given interval is greater than the minimum interval
        if ($interval_end >= $min_end)
        {
          return $value;
        }

        // we found a non-null value, stop the iteration process
        break;
      }
    }

    // all the values are equal to 0, the interval is incorrect
    throw new sfValidatorError($this, 'invalid', array('interval' => $value));
  }
} // END OF PluginChCmsParamDateIntervalValidator
