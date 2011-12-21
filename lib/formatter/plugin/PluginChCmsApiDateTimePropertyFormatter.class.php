<?php
/**
 * This file declare the PluginChCmsApiDateTimePropertyFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * Class with no description
 */
class PluginChCmsApiDateTimePropertyFormatter extends chCmsApiPropertyFormatter
{
  /**
   * constructor
   *
   * @return void
   */
  public function __construct($field, $options = array())
  {
    parent::__construct($field, array_merge(array(
            'format'  => DateTime::ISO8601), $options));
  }

  /**
   * format
   *
   * @return mixed
   */
  public function format($date)
  {
    $format = $this->getOption('format');

    $dateObj = parent::format($date);
    if (!($dateObj instanceof DateTime))
    {
      $dateObj = new DateTime($dateObj);
    }
    return $dateObj->format($format);
  }
} // END OF PluginChCmsApiDateTimePropertyFormatter
