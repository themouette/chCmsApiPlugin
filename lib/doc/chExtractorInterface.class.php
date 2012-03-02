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
  public function extract($object, $options = array());
}