<?php
/**
 * This file declare the chCmsApiTools class.
 *
 * @package chCmsApplicationPlugin
 * @subpackage api
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-05-29
 */

/**
 * defines a set of tools, useful for API generation
 */
class chCmsApiTools
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
    return array_merge(array('json', 'jsonp', 'xml'), $extra);
  }

  /**
   * return the sf_format requirement for route
   *
   * @return string
   */
  public static function getFormatRequirementForRoute($extra = array())
  {
    return join('|', self::getAvailableFormats($extra));
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
   * format given array result for current request format.
   *
   * @param array     $results  current results to render.
   * @param sfRequest $request  the current request.
   * @param sfResponse $response  the current response.
   * @return sfView::NONE
   */
  public static function formatResultForRequest($results, $request, $response)
  {
    chCmsApiTools::setResponseContentType($request, $response);
    switch ($request->getRequestFormat())
    {
      case 'json':
      case 'jsonp':
        $callback = $request->getParameter('jsonp', null);
        $result = json_encode($results);

        if (!is_null($callback))
        {
          $result = sprintf('%s(%s);', $callback, json_encode($results));
        }
        break;
      case 'xml':
        $result = self::array_to_xml(
                    $results,
                    new SimpleXMLElement(sprintf(
                            "<%sxml version=\"1.0\" encoding=\"%s\"%s><root/>",
                            '?', // to prevent issue with short open tag
                            $response->getCharset(),
                            '?' // to prevent issue with short open tag
                          )
                      ))->asXML();
        break;

      default:
        throw new sfException(sprintf('Unknown format "%s"', $request->getRequestFormat()));
        break;
    }

    return $result;
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
} // END OF chCmsApiTools
