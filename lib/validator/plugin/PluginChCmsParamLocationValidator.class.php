<?php
/**
 * This file declare the PluginChCmsParamLocationValidator class.
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
class PluginChCmsParamLocationValidator extends chCmsValidatorApiBase
{
  /**
   * configure the widget.
   * add following options :
   *  - min_length
   * -  max_length
   * add following messages:
   * - invalid
   * - server_error
   * - max_length
   * - min_length
   *
   * @param array $options  the validator options
   * @param array $messages error messages
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addoption('min_length', 3);
    $this->addoption('max_length', 255);
    $this->setMessage('invalid', 'Invalid data "%message%".');
    $this->setMessage('required', 'You must provide a location.');
    $this->addMessage('server_error', 'geocoding server error "%message%".');
    $this->addMessage('max_length', '"%value%" is too long (%max_length% characters max).');
    $this->addMessage('min_length', '"%value%" is too short (%min_length% characters min).');
  }

  /**
   * do clean
   */
  protected function doClean($value)
  {
    try
    {
      $validator = new sfValidatorString(array(
        'min_length' => $this->getOption('min_length'),
        'max_length' => $this->getOption('max_length')));

      $value = $validator->clean($value);

      try
      {
        // reverse geocode
        $r = Geocoder::encode($value);

        // extract first placemark
        return array(
          'lat'   => $r->results[0]->geometry->location->lat,
          'long'  => $r->results[0]->geometry->location->lng);
      }
      catch (GeocoderException $e)
      {
        if ('ZERO_RESULTS' === $e->getGeocoderError())
        {
          throw new sfValidatorError($this, 'invalid', array('message' => $e->getMessage()));
        }

        throw new sfValidatorError($this, 'server_error', array('message' => $e->getMessage()));
      }

      return $value;
    }
    catch (sfValidatorError $e)
    {
      throw new sfValidatorError($this, $e->getCode(), $e->getArguments());
    }
  }
} // END OF PluginChCmsParamLocationValidator