<?php
/**
 * This file declare the chCmsApiMethodAsPropertyFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-04
 */

/**
 * Class with no description
 */
class chCmsApiMethodAsPropertyFormatter extends chCmsApiPropertyFormatter
{
  /**
   * extract property from object
   *
   * @return mixed
   */
  protected function extractProperty($object)
  {
    $field_name = $this->getOption('field_name');

    if (is_callable(array($object, $field_name)))
    {
      return $object->$field_name();
    }

    return parent::extractProperty($object, $field_name);
  }
} // END OF chCmsApiMethodAsPropertyFormatter
