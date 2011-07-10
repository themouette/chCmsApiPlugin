<?php
/**
 * This file declare the chCmsApiPluginResponse class.
 *
 * @package chCmsApiPlugin
 * @subpackage response
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-09
 */

/**
 * plugin extension for the response
 * main extension are:
 * - setContentTypeForFormat
 * - setApiResultContent
 */
class chCmsApiPluginResponse
{
  /**
   * listen to response.method_not_found event and call plugin function
   * if exists.
   * this method is set up in chCmsApiPluginConfiguration::initialize
   *
   * @param sfEvent $event the response.method_not_found event.
   */
  public static function methodNotFound(sfEvent $event)
  {
    if (method_exists('chCmsApiPluginResponse', $event['method']))
    {
      $event->setReturnValue(call_user_func_array(
        array('chCmsApiPluginResponse', $event['method']),
        array_merge(array($event->getSubject()), $event['arguments'])
      ));
      return true;
    }
  }

  /**
   * set the content type from format
   *
   * @param string $sf_format request format to set content type for
   * @return sfResponse
   **/
  public static function setContentTypeForFormat($sf_response, $sf_format)
  {
    switch ($sf_format)
    {
      case 'form':
        $sf_response->setContentType('application/x-www-form-urlencoded');
        break;
      case 'json':
      case 'jsonp':
        $sf_response->setContentType('application/json');
        break;
      case 'xml':
        $sf_response->setContentType('text/xml');
        break;
      default:
        // let default process
        return false;
    }

    return $sf_response;
  }

  /**
   * format $result for given $format.
   *
   * @param mixed   $result     api result to format
   * @param sfRequest $request    the current request
   * @return sfResponse
   **/
  public static function setApiResultContent($sf_response, $result, $request)
  {
    $sf_response->setContent($sf_response->formatApiResultForRequest($result, $request));
    return $sf_response;
  }

  /**
   * format $result in $format
   * to add formats, just extend response with formatter.
   * a formatter is a response function in format name:
   * - formatApiResult%Format%
   * it only take $result and $request as argument and should return a string.
   *
   * Warning: a null result will set no content, not return an encoded null value.
   *
   * @param mixed     $result     api result to format
   * @param sfRequest $request    the current request
   * @return String
   **/
  public static function formatApiResultForRequest($sf_response, $result, $request)
  {
    if (is_null($result))
    {
      $sf_response->setContent(null);
      return '';
    }

    $format = $request->getRequestFormat();

    // well, try to call given format
    $method = sprintf('formatApiResult%s', sfInflector::camelize($format));

    return call_user_func(array($sf_response, $method), $result, $request);
  }

  /**
   * format response in form urlencoded format
   *
   * @param mixed   $result     api result to format
   * @param sfRequest $request    the current request
   * @return String
   **/
  public static function formatApiResultForm($sf_response, $result, $request)
  {
    return http_build_query($result);
  }

  /**
   * format response in json format
   * also handle jsonp.
   *
   * @param mixed   $result     api result to format
   * @param sfRequest $request    the current request
   * @return String
   **/
  public static function formatApiResultJson($sf_response, $result, $request)
  {
    $callback = $request->getParameter('jsonp', null);
    $result = json_encode($result);

    if (!is_null($callback))
    {
      $result = sprintf('%s(%s);', $callback, $result);
    }
    return $result;
  }

  /**
   * format response in jsonp format
   *
   * @param mixed   $result     api result to format
   * @param sfRequest $request    the current request
   * @return String
   **/
  public static function formatApiResultJsonp($sf_response, $result, $request)
  {
    return $sf_response->formatApiResultJson($result, $request);
  }

  /**
   * format response in xml format
   *
   * @param mixed   $result     api result to format
   * @param sfRequest $request    the current request
   * @return String
   **/
  public static function formatApiResultXml($sf_response, $result, $request)
  {
    $document = sprintf("<%sxml version=\"1.0\" encoding=\"%s\"%s><root/>",
                  // tricks php short open tags
                  '?', $sf_response->getCharset(), '?');
    $document = new SimpleXMLElement($document);

    $sf_response->processApiXmlValue($result, $document);

    return $document->asXML();
  }

  /**
   * transform result into xml for api
   *
   * @return void
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  public static function processApiXmlValue($sf_response, $result, $document, $defaultKey = 'result')
  {
    if (is_array($result) || is_object($result))
    {
      foreach ($result as $k => $v)
      {
        $k = (is_numeric($k) ? $default_key : $k);
        is_array($v) || is_object($v)
              ? $sf_response->processApiXmlValue($v, $document->addChild($k), $default_key)
              : $document->addChild($k, $v);
      }
    }
    else
    {
      $document->addChild($defaultKey, $result);
    }
  }
} // END OF chCmsApiPluginResponse
