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
class PluginChRouteDocumentationExtractor implements chExtractorInterface
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
      'ROUTE_DESCRIPTION' => $this->getDescription($route),
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

  protected function getDescription($route)
  {
    $options = $route->getOptions();
    return empty($options['comment']) ? '' : $options['comment'];
  }
}
