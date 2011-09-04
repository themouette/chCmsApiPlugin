<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color());

class TestchCmsApiGenerateFormatterTask extends chCmsApiGenerateFormatterTask
{
  public function __construct(){}

  public function getFormatterClassname($name) {return parent::getFormatterClassname($name);}
  public function getGenerationPath($arguments, $options) {return parent::getGenerationPath($arguments, $options);}
}

$t->diag('test utility methods');

$t->diag('method getFormatterClassname');
$task = new TestchCmsApiGenerateFormatterTask();
$t->is($task->getFormatterClassname('class'), 'classFormatter', 'append "Formatter" to class name');
$t->is($task->getFormatterClassname('classFormatter'), 'classFormatter', 'do not append "Formatter" if already there');
$t->is($task->getFormatterClassname('Formatterclass'), 'FormatterclassFormatter', 'append "Formatter" if not at the end');

$t->diag('method getGenerationPath');
$t->is($task->getGenerationPath(array(), array()), sfConfig::get('sf_lib_dir'). '/lib/formatter', '"lib/formatter" is default destination');
$t->is($task->getGenerationPath(array(), array('plugin' => 'sfTestPlugin')), sfConfig::get('sf_plugins_dir'). '/sfTestPlugin/lib/formatter', '"lib/formatter" is default destination for plugins');
try
{
  $task->getGenerationPath(array(), array('plugin' => 'sfUnknownPlugin'));
  $t->fail('unknown plugin should throw an InvalidArgumentException');
}
catch(InvalidArgumentException $e)
{
  $t->pass('unknown plugin throws InvalidArgumentException');
}
