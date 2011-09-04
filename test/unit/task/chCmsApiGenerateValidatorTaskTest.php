<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(9, new lime_output_color());

class TestchCmsApiGenerateValidatorTask extends chCmsApiGenerateValidatorTask
{
  public function __construct(){}

  public function getValidatorClassname($name) {return parent::getValidatorClassname($name);}
  public function getGenerationLibPath($arguments, $options) {return parent::getGenerationLibPath($arguments, $options);}
  public function getGenerationTestPath($arguments, $options) {return parent::getGenerationTestPath($arguments, $options);}
}

$t->diag('test utility methods');

$t->diag('method getValidatorClassname');
$task = new TestchCmsApiGenerateValidatorTask();
$t->is($task->getValidatorClassname('class'), 'classValidator', 'append "Validator" to class name');
$t->is($task->getValidatorClassname('classValidator'), 'classValidator', 'do not append "Validator" if already there');
$t->is($task->getValidatorClassname('Validatorclass'), 'ValidatorclassValidator', 'append "Validator" if not at the end');

$t->diag('method getGenerationLibPath');
$t->is($task->getGenerationLibPath(array(), array()), sfConfig::get('sf_lib_dir'). '/validator', '"lib/validator" is default destination');
$t->is($task->getGenerationLibPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/lib/validator', '"lib/validator" is default destination for plugins');
try
{
  $task->getGenerationLibPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch(InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}

$t->diag('method getGenerationTestPath');
$t->is($task->getGenerationTestPath(array(), array()), sfConfig::get('sf_test_dir'). '/unit/validator', '"test/unit/validator" is default destination');
$t->is($task->getGenerationTestPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/test/unit/validator', '"test/unit/validator" is default destination for plugins');
try
{
  $task->getGenerationTestPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch(InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}
