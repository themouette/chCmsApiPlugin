<?php

// try to include the command line argument symfony
if (file_exists(dirname(__FILE__).'/sf_test_lib.inc'))
{
  include(dirname(__FILE__).'/sf_test_lib.inc');
}

if (!isset($_SERVER['SYMFONY']))
{
  $project_default = dirname(dirname(dirname(__FILE__))) . '/../../lib/vendor/symfony/lib';
  if (!file_exists($project_default))
  {
    throw new RuntimeException('Could not find symfony core libraries.');
  }

  // set project default symfony lib
  $_SERVER['SYMFONY'] = $project_default;
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

$configuration = new sfProjectConfiguration(dirname(__FILE__).'/../fixtures/project');
require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function chCmsApiPlugin_autoload_again($class)
{
  $autoload = sfSimpleAutoload::getInstance();
  $autoload->reload();
  return $autoload->autoload($class);
}
spl_autoload_register('chCmsApiPlugin_autoload_again');
date_default_timezone_set('Europe/Paris');

if (file_exists($config = dirname(__FILE__).'/../../config/chCmsApiPluginConfiguration.class.php'))
{
  require_once $config;
  $plugin_configuration = new chCmsApiPluginConfiguration($configuration, dirname(__FILE__).'/../..', 'chCmsApiPlugin');
}
else
{
  $plugin_configuration = new sfPluginConfigurationGeneric($configuration, dirname(__FILE__).'/../..', 'chCmsApiPlugin');
}
