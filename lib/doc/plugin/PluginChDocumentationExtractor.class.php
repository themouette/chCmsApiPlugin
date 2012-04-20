<?php
/**
 * This file declare the PluginChDocumentationExtractor class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-02-17
 */

/**
 * Class used extract documentation information from different sources.
 */
class PluginChDocumentationExtractor implements chExtractorInterface
{
  protected $extractors = array();


  /**
   * Tells if the current extractor supports/can extract data from the given
   * object.
   *
   * @param mixed $object The object to test.
   *
   * @return bool True if the extract() method can be called, false otherwise.
   */
  public function supports($object)
  {
    foreach ($this->extractors as $extractor)
    {
      if ($extractor->supports($object))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * Extract documentation oriented data from the given object.
   *
   * @param mixed $object   The object to introspect.
   * @param array $options  The extract options.
   *
   * @return array The extracted data.
   */
  public function extract($object, $options = array())
  {
    $data = array();

    if (!$object)
    {
      return $data;
    }

    foreach ($this->extractors as $extractor)
    {
      if ($extractor->supports($object))
      {
        $data = array_merge($data, $extractor->extract($object, $options));
      }
    }

    return $data;
  }

  public function extractFromRequest(sfRequest $request, $options = array())
  {
    $route = $request->getAttribute('sf_route');
    $paramValidator = $request->getParamValidator();
    $context_data = $request->getRequestContext();

    return array_merge(
      array('AUTH_REQUIRED' => $context_data['is_secure']),
      $this->extract($route, $options),
      $this->extract($paramValidator, $options)
    );
  }

  public function registerExtractor($name, chExtractorInterface $extractor)
  {
    $this->extractors[$name] = $extractor;
  }
}
