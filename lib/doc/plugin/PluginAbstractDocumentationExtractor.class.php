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
  protected $options = array();


  public function __construct($options = array())
  {
    $this->options = $options;
  }

  protected function getDescription($object, $try_class = true)
  {
    if ($description = $object->getOption('comment'))
    {
      return $this->parseDescription($description);
    }

    if (!$try_class)
    {
      return '';
    }

    return $this->parseDescription($this->getDescriptionFromClass($object));
  }

  protected function getDescriptionFromClass($class, $try_parent = true)
  {
    $rClass = $class instanceof ReflectionClass ? $class : new ReflectionClass($class);

    if ($try_parent)
    {
      return $this->getDescriptionFromClass($rClass->getParentClass(), false);
    }

    return $this->cleanDocBlock($rClass->getDocComment());
  }

  protected function getDescriptionFromMethod($class, $method)
  {
    $rClass = new ReflectionClass($class);
    return $this->cleanDocBlock($rClass->getMethod($method)->getDocComment());
  }

  /**
   * Clean a documentation block to only return its text.
   *
   * @param string $docBlock The raw doc block.
   *
   * @return string The cleaned doc block.
   * @author Kevin Gomez <kevin_gomez@carpe-hora.com>
   */
  protected function cleanDocBlock($docBlock)
  {
    $docBlock = trim(str_replace(array('/**', '/*', '*/'), '', $docBlock));
    return preg_replace('#^[ ]*\*[ ]*#m', '', $docBlock);
  }

  protected function parseDescription($description)
  {
    return $this->getDescriptionParser()->transform($description);
  }

  protected function getDescriptionParser()
  {
    if (isset($this->options['description_parser']))
    {
      return $this->options['description_parser'];
    }

    return $this->options['description_parser'] = new Markdown_Parser();
  }
}
