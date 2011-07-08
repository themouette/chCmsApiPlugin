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
    foreach (array(/* list your modules here */) as $module)
    {
      if (in_array($module, sfConfig::get('sf_enabled_modules')))
      {
        call_user_func(array('chCmsApiPluginRouting',sprintf('prepend%sRoutes', ucfirst($module))), $event->getSubject());
      }
    }
  }

  /* define your prependMyModule($routing) methods to register routes */
}
