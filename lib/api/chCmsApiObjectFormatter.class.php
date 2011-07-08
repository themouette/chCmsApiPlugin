<?php
/**
 * This file declare the chCmsApiObjectFormatter class.
 *
 * @package chCmsApplicationPlugin
 * @subpackage api
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-05-30
 */

/**
 * a simple formatter for api results
 */
class chCmsApiObjectFormatter
{
  /**
   * format object into an array, including given fields only
   *
   * @return array
   **/
  public static function formatObject($object, $fields)
  {
    if (!is_array($object) && is_callable(array($object, 'toArray')))
    {
      $object = $object->toArray(BasePeer::TYPE_FIELDNAME, true, array(), true);
    }

    $result = array();
    foreach ($fields as $id => $key)
    {
      if (is_array($key))
      {
        $result[$id] = isset($object[$id])
                        ? chCmsApiObjectFormatter::formatCollection($object[$id], $key)
                        : array();
      }
      else
      {
        $result[$key] = isset($object[$key])
                        ? $object[$key]
                        : null;
      }
    }
    return $result;
  }

  /**
   * filters an object collection to return an array
   * presenting only the wanted fields
   *
   * @return array
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  public static function formatCollection($collection, $fieldnames)
  {
    if (!is_array($collection) && is_callable(array($collection, 'toArray')))
    {
      $colection = $collection->toArray(null, null, BasePeer::TYPE_FIELDNAME, true);
    }

    $results = array();
    foreach ($collection as $key => $object)
    {
      $results[$key] = chCmsApiObjectFormatter::formatObject($object, $fieldnames);
    }

    return $results;
  }
} // END OF chCmsApiObjectFormatter
