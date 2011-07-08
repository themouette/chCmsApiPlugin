<?php
$pluginDir = dirname(dirname(dirname(dirname(__FILE__))));
require $pluginDir . '/test/bootstrap/unit.php';
require $pluginDir . '/../chCmsBasePlugin/lib/pluginchCmsActions.class.php';
require $pluginDir . '/../../lib/chCmsActions.class.php';

$t = new lime_test(8, new lime_output_color());

// create a fake action for tests
class testApiAction extends chCmsApiActions
{
  public function __construct()
  {
  }

  public function getDefaultErrorCode()
  {
    return 'testApiAction';
  }

  protected function formatResult($result)
  {
    return serialize($result);
  }
}

$action = new testApiAction();

$t->diag('test chCmsApiActions');

$t->info('test forward406');
try
{
  $action->forward406('message', 'code');
  $t->fail('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->pass('a chCmsError406Exception was thrown');
  $message = unserialize($e->getMessage());
  $t->is_deeply(
    $message,
    array('error' => array('code' => 'code', 'message' => 'message')),
    'returned error is what expected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

try
{
  $action->forward406('message', 'code', 'params');
  $t->fail('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->pass('a chCmsError406Exception was thrown');
  $message = unserialize($e->getMessage());
  $t->is_deeply(
    $message,
    array('error' => array('code' => 'code', 'message' => 'message', 'parameters' => 'params')),
    'returned error is what expected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

$t->comment('test forward406If');
try
{
  $action->forward406If(true, 'message', 'code', 'params');
  $t->fail('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->pass('action was redirected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

try
{
  $action->forward406If(false, 'message', 'code', 'params');
  $t->pass('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->fail('action was redirected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

$t->comment('test forward406Unless');
try
{
  $action->forward406Unless(false, 'message', 'code', 'params');
  $t->fail('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->pass('action was redirected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

try
{
  $action->forward406Unless(true, 'message', 'code', 'params');
  $t->pass('actions was not redirected');
}
catch (chCmsError406Exception $e)
{
  $t->fail('action was redirected');
}
catch(Exception $e)
{
  $t->fail('unknown exception was thrown');
}

