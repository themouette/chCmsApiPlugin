<?php
/**
 * This file declare the PluginChRouteDocumentationExtractor class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-02-17
 */

/**
 * Class used extract documentation information from a route.
 */
class PluginChRouteDocumentationExtractor extends AbstractDocumentationExtractor implements chExtractorInterface
{
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
    return $object instanceof sfRoute;
  }

  /**
   * Extract documentation oriented data from the given object.
   *
   * @param mixed $object   The object to introspect.
   * @param array $options  The extract options.
   *
   * @return array The extracted data.
   */
  public function extract($route, $options = array())
  {
    return array(
      'HTTP_METHODS'      => $this->getHttpMethods($route),
      'FORMAL_URL'        => $route->getPattern(),
      'SUPPORTED_FORMATS' => $this->getFormats($route),
      'DEFAULT_FORMAT'    => $this->getDefaultFormat($route),
      'ROUTE_DESCRIPTION' => $this->getRouteDescription($route),
    );
  }

  protected function getHttpMethods($route)
  {
    $requirements = $route->getRequirements();
    return array_map('strtoupper', $requirements['sf_method']);
  }

  protected function getDefaultFormat($route)
  {
    $defaults = $route->getDefaults();
    return $defaults['sf_format'];
  }

  protected function getFormats($route)
  {
    $requirements = $route->getRequirements();
    $formats_regex = $requirements['sf_format'];

    $formats = str_replace(array('(?:', ')'), '', $formats_regex);
    return explode('|', $formats);
  }

  protected function getRouteDescription($route)
  {
    $options = $route->getOptions();

    // first, check if a comment has been given
    if (!empty($options['comment']))
    {
      return $options['comment'];
    }

    // if not, check the action's docbloc
    $defaults = $route->getDefaults();

    // retrieve the action instance from the module and action name
    try
    {
      $action = $this->options['context']
        ->getController()
        ->getAction($defaults['module'], $defaults['action'], 'action');

      $doc = $this->getDescriptionFromMethod($action, 'execute'.ucfirst($defaults['action']));
      if (!empty($doc))
      {
        return $doc;
      }
    }
    catch (Exception $e)
    {
    }

    // nothing has been found
    return '';
  }
}
