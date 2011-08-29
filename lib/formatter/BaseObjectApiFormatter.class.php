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
class BaseObjectApiFormatter
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
   * @param array|null $extension fields to extend current fields with
   * @return array
   **/
  public function getFormatFields($extension = null)
  {
    if (!is_null($extension))
    {
      $this->setDefaultFormatFields($extension);
      return $this->mergeFieldsArray($extension);
    }
    return $this->formatFields;
  }

  /**
   * add fields to format
   *
   * @return BaseObjectApiFormatter
   **/
  public function mergeFormatFields($formatFields)
  {
    $this->setFormatFields($this->mergeFieldsArray($formatFields));

    return $this;
  }

  /**
   * merge fields array
   *
   * @return array
   **/
  protected function mergeFieldsArray($fields = array())
  {
    $fields = is_array($fields) ? $fields : array();
    if (!is_null($fields))
    {
      $this->setDefaultFormatFields($fields);
    }
    return array_merge($this->getFormatFields(), $fields);
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
    $formatFields = $this->mergeFieldsArray($formatFields);
    $object = $this->objectToArray($object);

    $result = new stdClass();
    foreach ($formatFields as $id => $key)
    {
      if (is_array($key) || $key instanceof BaseObjectApiFormatter)
      {
        $formatter  = is_array($key) ? new BaseObjectApiFormatter($key) : $key;
        $result->$id = $formatter->formatCollection(isset($object[$id]) ? $object[$id] : array());
      }
      else
      {
        // assume this is a sacalar value
        $result->$key = isset($object[$key]) ? $object[$key] : null;
      }
    }

    return $result;
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
    $result = array();
    foreach ($collection as $key => $object)
    {
      $result[$key] = $this->formatObject($object, $formatFields);
    }

    return $result;
  }

  /**
   * transform object to key value array.
   *
   * @param Object $object object to transform.
   * @return array
   **/
  public function objectToArray($object)
  {
    if (is_array($object))
    {
      return $object;
    }

    if (is_callable(array($object, 'toArray')))
    {
      return $object->toArray(BasePeer::TYPE_FIELDNAME, true, array(), true);
    }

    // it has to be iterable
    $result = array();
    foreach ($object as $key => $value)
    {
      $result[$key] = $value;
    }

    return $result;
  }
} // END OF BaseObjectFormatter
