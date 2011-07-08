<?php

/**
 * chCmsApiPlugin configuration.
 *
 * @package     chCmsApiPlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id$
 */
class chCmsApiPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    /*
    keep just in case
    $this->dispatcher->connect(
        'user.method_not_found',
        array('chCmsApiPluginUser', 'methodNotFound'));

    $this->dispatcher->connect(
        'routing.load_configuration',
        array('chCmsApiPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
    */
  }
}
