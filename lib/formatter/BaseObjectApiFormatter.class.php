<?php
/**
 * This file declare the BaseObjectFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-08
 */

/**
 * the base formatter object.
 * To declare a formatter, just extend this class and setDefaultFormatFields
 * in the initialize method.
 */
abstract class BaseObjectApiFormatter
{
  protected $formatFields;

  /**
   * set format for this instance
   *
   * @param array $format set fields
   * @return BaseObjectFormatter
   **/
  public function setFormatFields($formatFields)
  {
    $this->formatFields = $formatFields;
    return $this;
  }

  /**
   * set format for this instance
   *
   * @param array $format set fields
   * @return BaseObjectFormatter
   **/
  public function setDefaultFormatFields($formatFields)
  {
    if (!$this->formatFields)
    {
      $this->setFormatFields($formatFields);
    }

    return $this;
  }

  /**
   * retriev formatFields
   *
   * @param array|null $format fields to use to override current fields
   * @return array
   **/
  public function getFormatFields($formatFields = null)
  {
    if (!is_null($formatFields))
    {
      $this->setDefaultFormatFields($formatFields);
      return $formatFields;
    }
    return $this->formatFields;
  }

  /**
   * constructor
   **/
  public function __construct($formatFields = null)
  {
    $this->setDefaultFormatFields($formatFields);

    $this->initialize();
  }

  /**
   * declare default fields to render here
   **/
  public function initialize()
  {
  }

  /**
   * format object
   *
   * @param BaseObject|array        $object object to format
   * @param array|null              $formatFields     fields to use
   * @return array
   **/
  public function formatObject($object, $formatFields = null)
  {
    return chCmsApiObjectFormatter::formatObject($object, $this->getFormatFields($formatFields));
  }

  /**
   * format a collection
   *
   * @param PropelCollection|array  $collection collection to format
   * @param array|null              $formatFields     fields to use
   * @return array
   **/
  public function formatCollection($collection, $formatFields = null)
  {
    return chCmsApiObjectFormatter::formatCollection($collection, $this->getFormatFields($formatFields));
  }
} // END OF BaseObjectFormatter
