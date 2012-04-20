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
  public function extract($route, $options = array())
  {
    if (!$route instanceof sfRoute)
    {
      return array();
    }

    return array(
      'HTTP_METHODS'      => $this->getHttpMethods($route),
      'FORMAL_URL'        => $this->getFormalUrl($route),
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

  protected function getFormalUrl($route)
  {
    return $route->getPattern();
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
      $action = sfContext::getInstance()
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
