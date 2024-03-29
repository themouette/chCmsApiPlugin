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
    $this->dispatcher->connect('request.method_not_found', array(
      'chCmsApiPluginRequest', 'methodNotFound'
    ));

    $this->dispatcher->connect('response.method_not_found', array(
      'chCmsApiPluginResponse', 'methodNotFound'
    ));

    $this->dispatcher->connect('context.load_factories', array(
      'chCmsApiParamValidator', 'listenToLoadFactoryEvent'
    ));

    $this->dispatcher->connect('routing.load_configuration', array(
      'chCmsApiPluginRouting', 'listenToRoutingLoadConfigurationEvent'
    ));

    $this->dispatcher->connect('api.request.pre', array(
      'chCmsApiPluginRequest', 'decodeJson'
    ));
  }
}