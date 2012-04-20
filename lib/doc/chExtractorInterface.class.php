<?php
/**
 * This file declare the chExtractorInterface interface.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-03-01
 */


interface chExtractorInterface
{
  /**
   * Extract documentation oriented data from the given object.
   *
   * @param mixed $object   The object to introspect.
   * @param array $options  The extract options.
   *
   * @return array The extracted data.
   */
  public function extract($object, $options = array());

  /**
   * Tells if the current extractor supports/can extract data from the given
   * object.
   *
   * @param mixed $object The object to test.
   *
   * @return bool True if the extract() method can be called, false otherwise.
   */
  public function supports($object);
}