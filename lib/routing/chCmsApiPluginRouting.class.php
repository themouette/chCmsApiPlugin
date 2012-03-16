<?php

/**
 * This file declare the chCmsApiPluginRouting class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  routing
 * @author      Your name here
 * @version     SVN: $Id$
 */

/**
 * static methods used to register chCmsApiPlugin routes
 */
class chCmsApiPluginRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    foreach (array('apiDoc') as $module)
    {
      if (in_array($module, sfConfig::get('sf_enabled_modules')))
      {
        call_user_func(array('chCmsApiPluginRouting',sprintf('prepend%sRoutes', ucfirst($module))), $event->getSubject());
      }
    }
  }


  public static function prependApiDocRoutes($routing)
  {
    $routing->prependRoute(
      'api_method_doc',
      new sfRequestRoute(
        '/api/doc/method/:route',
        array('module' => 'apiDoc', 'action' => 'methodDoc'),
        array('sf_method' => array('GET'))
      )
    );

    $routing->prependRoute(
      'api_formatter_doc',
      new sfRequestRoute(
        '/api/doc/formatter/:formatter',
        array('module' => 'apiDoc', 'action' => 'formatterDoc'),
        array('sf_method' => array('GET'))
      )
    );
  }
}
