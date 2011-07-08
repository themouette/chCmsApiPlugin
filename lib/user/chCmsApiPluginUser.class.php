<?php

/**
 * This file declare the chCmsApiPluginUser class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  user
 * @author      Your name here
 * @version     SVN: $Id$
 */

/**
 * static methods used to register chCmsApiPlugin user function
 */
class chCmsApiPluginUser
{
  /**
   * listen to user.method_not_found event and call plugin function 
   * if exists.
   * this method is set up in chCmsApiPluginConfiguration::initialize
   *
   * @param sfEvent $event the user.method_not_found event.
   */
  public static function methodNotFound(sfEvent $event)
  {
    if (method_exists('chCmsApiPluginUser', $event['method']))
    {
      $event->setReturnValue(call_user_func_array(
        array('chCmsApiPluginUser', $event['method']),
        array_merge(array($event->getSubject()), $event['arguments'])
      ));
      return true;
    }
  }

  /* define here your user methods. */
}
