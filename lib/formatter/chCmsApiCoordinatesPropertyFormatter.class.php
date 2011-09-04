<?php
/**
 * This file declare the chCmsApiCoordinatesPropertyFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * formatter for coordinates
 */
class chCmsApiCoordinatesPropertyFormatter extends chCmsApiAggregatePropertyFormatter
{
  /**
   * constructor
   *
   * @return void
   */
  public function __construct($options = array())
  {
    $options = array_merge(array(
            'latitude_field'  => 'latitude',
            'longitude_field' => 'longitude'), $options);
    parent::__construct(array(
        'lat' => new chCmsApiPropertyFormatter($options['latitude_field']),
        'long' => new chCmsApiPropertyFormatter($options['longitude_field']),), $options);
  }
} // END OF chCmsApiCoordinatesPropertyFormatter
