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
} // END OF chCmsApiRequest
