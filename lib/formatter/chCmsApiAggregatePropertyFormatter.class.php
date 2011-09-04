<?php
/**
 * This file declare the chCmsApiAggregatePropertyFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * an aggregate formatter to merge 2 fields
 */
class chCmsApiAggregatePropertyFormatter extends chCmsApiFormatterPropertyFormatter
{
  /**
   * constructor
   *
   * @param array|chCmsApiObjectFormatter $formatters the formatters to aggregate
   * @param array                         $options    the options to use
   * @return void
   */
  public function __construct($formatters, $options = array())
  {
    if (!($formatters instanceof chCmsApiObjectFormatter))
    {
      $formatters = new chCmsApiObjectFormatter($formatters, $options);
    }

    parent::__construct(null, $formatters, $options);
  }

  /**
   * extract property from object
   *
   * @return mixed
   */
  protected function extractProperty($object)
  {
    return $object;
  }
} // END OF chCmsApiAggregatePropertyFormatter
