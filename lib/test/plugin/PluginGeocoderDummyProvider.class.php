<?php
/**
 * This file declare the PluginGeocoderDummyProvider class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  test
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-05-23
 */


class PluginGeocoderDummyProvider extends \Geocoder\Provider\AbstractProvider implements \Geocoder\Provider\ProviderInterface
{
  public $data = array();
  public $reversed_data = array();


  public function __construct()
  {
  }

  public function getGeocodedData($address)
  {
    if (!empty($this->data[$address]))
    {
      return $this->data[$address];
    }

    return $this->getDefaults();
  }

  public function getReversedData(array $coordinates)
  {
    if (!empty($this->reversed_data[$address]))
    {
      return $this->reversed_data[$address];
    }

    return $this->getDefaults();
  }

  public function getName()
  {
    return 'dummy';
  }
} // END OF PluginGeocoderDummyProvider