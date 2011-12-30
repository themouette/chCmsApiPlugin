<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(9, new lime_output_color());

class TestchCmsApiGenerateValidatorTask extends chCmsApiGenerateParamValidatorTask
{
  public function __construct(){}

  public function getParamValidatorClassname($name) {return parent::getParamValidatorClassname($name);}
  public function getGenerationLibPath($arguments, $options) {return parent::getGenerationLibPath($arguments, $options);}
  public function getGenerationTestPath($arguments, $options) {return parent::getGenerationTestPath($arguments, $options);}
}

$t->diag('test utility methods');

$t->diag('method getParamValidatorClassname');
$task = new TestchCmsApiGenerateValidatorTask();
$t->is($task->getParamValidatorClassname('class'), 'classParamValidator', 'append "ParamValidator" to class name');
$t->is($task->getParamValidatorClassname('classParamValidator'), 'classParamValidator', 'do not append "ParamValidator" if already there');
$t->is($task->getParamValidatorClassname('ParamValidatorclass'), 'ParamValidatorclassParamValidator', 'append "ParamValidator" if not at the end');

$t->diag('method getGenerationLibPath');
$t->is($task->getGenerationLibPath(array(), array()), sfConfig::get('sf_lib_dir'). '/param', '"lib/param" is default destination');
$t->is($task->getGenerationLibPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/lib/param', '"lib/param" is default destination for plugins');
try
{
  $task->getGenerationLibPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch (InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}

$t->diag('method getGenerationTestPath');
$t->is($task->getGenerationTestPath(array(), array()), sfConfig::get('sf_test_dir'). '/unit/param', '"test/unit/param" is default destination');
$t->is($task->getGenerationTestPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/test/unit/param', '"test/unit/param" is default destination for plugins');
try
{
  $task->getGenerationTestPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch (InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}
