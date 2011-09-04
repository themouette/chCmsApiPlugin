<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color());

class TestchCmsApiGenerateValidatorTask extends chCmsApiGenerateValidatorTask
{
  public function __construct(){}

  public function getValidatorClassname($name) {return parent::getValidatorClassname($name);}
  public function getGenerationPath($arguments, $options) {return parent::getGenerationPath($arguments, $options);}
}

$t->diag('test utility methods');

$t->diag('method getValidatorClassname');
$task = new TestchCmsApiGenerateValidatorTask();
$t->is($task->getValidatorClassname('class'), 'classValidator', 'append "Validator" to class name');
$t->is($task->getValidatorClassname('classValidator'), 'classValidator', 'do not append "Validator" if already there');
$t->is($task->getValidatorClassname('Validatorclass'), 'ValidatorclassValidator', 'append "Validator" if not at the end');

$t->diag('method getGenerationPath');
$t->is($task->getGenerationPath(array(), array()), sfConfig::get('sf_lib_dir'). '/lib/validator', '"lib/validator" is default destination');
$t->is($task->getGenerationPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/lib/validator', '"lib/validator" is default destination for plugins');
try
{
  $task->getGenerationPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch(InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}

