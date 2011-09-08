<?php
/**
 * This file declare the PluginChCmsParamLocationOrCoordinatesValidator class.
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
class PluginChCmsParamLocationOrCoordinatesValidator extends chCmsValidatorApiBase
{
  /**
   * configure the widget.
   * add following options :
   *  - coordinates : the coordinates field
   *  - location : the location field
   *  - output : the result field
   *
   * add folowing messages
   *  - required
   *  - too_many
   *
   * @param array $options  the validator options
   * @param array $messages error messages
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('coordinates', 'coord');
    $this->addOption('location', 'location');
    $this->addOption('output', 'coord');

    $this->setMessage('required', 'You must provide a "%location%" or a "%coordinates%" field.');
    $this->addMessage('too_many', 'You must provide only one of "%location%" or "%coordinates%" field.');
  }

  /**
   * do clean
   */
  protected function doClean($values)
  {
    $coordinates  = $this->getOption('coordinates');
    $location     = $this->getOption('location');

    if (!isset($values[$coordinates]) && !isset($values[$location]))
    {
      $this->throwApiError('INVALID_PARAM', strtr($this->getMessage('required'), array(
                    '%location%' => $location,
                    '%coordinates%' => $coordinates)));
    }

    if (isset($values[$coordinates]) && isset($values[$location]))
    {
      $this->throwApiError('INVALID_PARAM', strtr($this->getMessage('too_many'), array(
                    '%location%' => $location,
                    '%coordinates%' => $coordinates)));
    }

    $values[$this->getOption('output')] = isset($values[$coordinates]) ? $values[$coordinates] : $values[$location];

    return $values;
  }
} // END OF PluginChCmsParamLocationOrCoordinatesValidator
