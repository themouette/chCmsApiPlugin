<?php
/**
 * This file declare the chCmsApiRequest class.
 *
 * @package chCmsApiPlugin
 * @subpackage request
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-09
 */

/**
 * plugin extension for the request
 */
class chCmsApiPluginRequest
{
  const PARAM_NAMESPACE = 'plugins.chCmsApiPlugin';

  /**
   * listen to request.method_not_found event and call plugin function
   * if exists.
   * this method is set up in chCmsApiPluginConfiguration::initialize
   *
   * @param sfEvent $event the request.method_not_found event.
   */
  public static function methodNotFound(sfEvent $event)
  {
    if (method_exists('chCmsApiPluginRequest', $event['method']))
    {
      $event->setReturnValue(call_user_func_array(
        array('chCmsApiPluginRequest', $event['method']),
        array_merge(array($event->getSubject()), $event['arguments'])
      ));
      return true;
    }
  }

  /**
   * return the route param validator if any
   *
   * @param sfRequest $sf_request the request object
   * @return chCmsApiParamValidator
   */
  public static function getParamValidator($sf_request)
  {
    return $sf_request->getAttribute('param_validator', null, chCmsApiPluginRequest::PARAM_NAMESPACE);
  }

  /**
   * set the route param validator into request
   *
   * @param sfRequest $sf_request the request object
   * @return sfRequest
   */
  public static function setParamValidator($sf_request, $validator)
  {
    $sf_request->setAttribute('param_validator', $validator, chCmsApiPluginRequest::PARAM_NAMESPACE);
    return $sf_request;
  }

  /**
   * return the original parameters before validation.
   *
   * @param sfRequest $sf_request the request object
   * @return array
   */
  public static function getOriginalApiParameters($sf_request)
  {
    return $sf_request->getAttribute('api_original_parameters', $request->getParameterHolder()->getAll(), chCmsApiPluginRequest::PARAM_NAMESPACE);
  }

  /**
   * set the original parameters before validation.
   *
   * @param sfRequest $sf_request the request object
   * @param array     $parameters parameters to set
   * @return sfRequest
   */
  public static function setOriginalApiParameters($sf_request, $parameters)
  {
    $sf_request->setAttribute('api_original_parameters', $parameters, chCmsApiPluginRequest::PARAM_NAMESPACE);
    return $sf_request;
  }
} // END OF chCmsApiRequest
