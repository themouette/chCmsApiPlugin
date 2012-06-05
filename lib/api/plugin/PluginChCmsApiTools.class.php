<?php
/**
 * This file declare the PluginChCmsApiTools class.
 *
 * @package     chCmsApplicationPlugin
 * @subpackage  api
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-05-29
 */

/**
 * defines a set of tools, useful for API generation
 */
class PluginChCmsApiTools
{
  /**
   * return the api default format in use
   *
   * @return String
   */
  public static function getDefaultFormat()
  {
    return 'json';
  }

  /**
   * return an array of available formats for this API.
   *
   * @param array $extra extra formats
   * @return array
   */
  public static function getAvailableFormats($extra = array())
  {
    $formats = array_merge(array('json', 'jsonp', 'xml'), $extra);

    if (sfConfig::get('sf_debug', false))
    {
      $formats[] = 'html';
    }

    return $formats;
  }

  /**
   * return the sf_format requirement for route
   *
   * @return string
   */
  public static function getFormatRequirementForRoute($extra = array())
  {
    return sprintf('(?:%s)',join('|', self::getAvailableFormats($extra)));
  }

  /**
   * set response contentype from request format
   *
   * @param sfRequest $request  the current request.
   * @param sfResponse $response  the current response.
   * @return void
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  static public function setResponseContentType($request, $response)
  {
    $response->setContentTypeForFormat($request->getRequestFormat());
  }

  /**
   * converts an array to xml nodes
   *
   * @param array $arr the array to convert
   * @param SimpleXMLElement the xml element to append elements to
   * @return SimpleXMLElement
   */
  public static function array_to_xml($arr, $xml, $default_key = "result")
  {
    foreach ($arr as $k => $v)
    {
      $k = (is_numeric($k) ? $default_key : $k);
      is_array($v) || is_object($v)
            ? self::array_to_xml($v, $xml->addChild($k), $default_key)
            : $xml->addChild($k, $v);
    }
    return $xml;
  }

  /**
   * Extract the API routes from a sfRouting instance.
   *
   * @param sfRouting $routing The routing object to introspect.
   *
   * @return array The API routes.
   */
  public function extractApiRoutes(sfRouting $routing)
  {
    $routes = array();
    foreach ($routing->getRoutes() as $id => $route)
    {
      if (is_string($route))
      {
        $route = unserialize($route);
      }

      $options = $route->getOptions();

      if (empty($options['param_validator']) || !$this->isApiMethodPublic($route))
      {
        continue;
      }

      $routes[$id] = $route;
    }

    return $routes;
  }

  /**
   * Checks if a given API route is public. A public route will be displayed on
   * the generated documentation whereas a private one won't.
   *
   * @param sfRoute $route The route to test.
   *
   * @return bool True if the route is public, false otherwise.
   */
  public function isApiMethodPublic(sfRoute $route)
  {
    $options = $route->getOptions();
    return !(array_key_exists('public_api', $options) && !$options['public_api']);
  }

  /**
   * Extract the API formatters from the current project.
   *
   * @return array The formatters.
   */
  public function extractApiFormatters()
  {
    $formatters = array();

    $classes = sfFinder::type('file')
      ->name('*Formatter{,.class}.php')
      ->in(sfConfig::get('sf_root_dir'));
    foreach ($classes as $file)
    {
      $class = explode(DIRECTORY_SEPARATOR, $file);
      $class = explode('.', $class[count($class) - 1]);
      $class = $class[0];

      if (!$this->isFormatterValid($class))
      {
        continue;
      }

      $formatters[] = $class;
    }

    sort($formatters, SORT_LOCALE_STRING);

    return $formatters;
  }

  /**
   * Tells if a given formatter is valid (ie: can be displayed in the doc).
   *
   * @param string $formatter The formatter name.
   *
   * @return bool
   * @author Kevin Gomez <kevin_gomez@carpe-hora.com>
   */
  public function isFormatterValid($formatter)
  {
    // skip Plugin* classes
    if (substr($formatter, 0, 6) === 'Plugin')
    {
      return false;
    }

    try
    {
      $rClass = new ReflectionClass($formatter);
      // a formatter must inherit from BasechCmsApiFormatter
      if ($rClass->isAbstract() || !$rClass->isSubclassOf('BasechCmsApiFormatter'))
      {
        return false;
      }
    }
    catch (ReflectionException $e)
    {
      return false;
    }

    return true;
  }
} // END OF PluginChCmsApiTools
