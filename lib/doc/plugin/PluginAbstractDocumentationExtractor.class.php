<?php
/**
 * This file declare the PluginAbstractDocumentationExtractor class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-03-16
 */

/**
 * Class used to provide some usefull tools for extractors.
 */
abstract class PluginAbstractDocumentationExtractor implements chExtractorInterface
{
  protected function getDescription($object, $try_class = true)
  {
    if ($description = $object->getOption('comment'))
    {
      return $description;
    }

    return $try_class ? $this->getDescriptionFromClass($object) : '';
  }

  protected function getDescriptionFromClass($class, $try_parent = true)
  {
    $rClass = $class instanceof ReflectionClass ? $class : new ReflectionClass($class);

    if ($try_parent)
    {
      return $this->getDescriptionFromClass($rClass->getParentClass(), false);
    }

    $raw_description = $rClass->getDocComment();

    $description = trim(str_replace(array('/**', '/*', '*/'), '', $raw_description));
    $description = preg_replace('#^[ ]*\*[ ]*#m', '', $description);

    return $description;
  }
}
