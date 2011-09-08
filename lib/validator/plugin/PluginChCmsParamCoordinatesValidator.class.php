<?php
/**
 * This file declare the PluginChCmsParamCoordinatesValidator class.
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
class PluginChCmsParamCoordinatesValidator extends chCmsValidatorApiBase
{
  /**
   * configure the widget.
   * add following options :
   *  * required
   *
   * available messages are:
   *  * invalid
   *  * bad_format
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addMessage('bad_format', 'please provide lat/long array');
    $this->addMessage('invalid', 'latitude or longitude is invalid. please provide a decimal coordinate array');
    $this->setMessage('required', 'You must provide coordinates.');
  }

  /**
   * coordinates is an array of parameters lat and long
   *
   * @return void
   */
  protected function doClean($value)
  {
    if (!is_array($value) || !isset($value['lat']) || !isset($value['long']))
    {
      throw new sfValidatorError($this, 'bad_format', array());
    }

    try
    {
      // valid ranges:
      // Latitude: -90.0000 to 90.0000
      // Longitude: -180.0000 to 180.0000
      $pattern = new sfValidatorRegex(array('pattern' => '#[-]?[0-9]*[.]{0,1}[0-9]{0,4}#'));
      $latIntervalValidator = new sfValidatorNumber(array('min' => -90, 'max' => 90));
      $longIntervalValidator = new sfValidatorNumber(array('min' => -180, 'max' => 180));

      return array(
        'lat'   => $latIntervalValidator->clean($pattern->clean($value['lat'])),
        'long'  => $longIntervalValidator->clean($pattern->clean($value['long']))
      );

    }
    catch(sfValidatorError $e)
    {
      throw new sfValidatorError($this, 'invalid', array());
    }
  }
} // END OF PluginChCmsParamCoordinatesValidator
